@extends('layouts.app')

@section('header', 'Approval Kasbon')

@section('content')
<div class="page-container animate-in">
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <h1 class="ph-title">Approval Kasbon</h1>
                <div class="ph-subtitle">Kelola pengajuan pinjaman karyawan dan integrasikan dengan sistem penggajian.</div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success animate-in">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger animate-in">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-card animate-in animate-in-delay-1">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Total Disetujui</div>
                    <div class="stat-value" style="color:#059669;">Rp {{ number_format($totalAmountApproved, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#059669;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="stat-card animate-in animate-in-delay-2">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Menunggu Persetujuan</div>
                    <div class="stat-value" style="color:#d97706;">{{ $totalPending }} <span style="font-size:0.85rem;font-weight:600;color:#94a3b8;">Pengajuan</span></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);color:#d97706;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="stat-card animate-in animate-in-delay-3">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Disetujui</div>
                    <div class="stat-value" style="color:#0f172a;">{{ $totalApproved }} <span style="font-size:0.85rem;font-weight:600;color:#94a3b8;">Kasbon</span></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#eef2ff,#e0e7ff);color:#4f46e5;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                </div>
            </div>
        </div>
        <div class="stat-card animate-in animate-in-delay-4">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Ditolak</div>
                    <div class="stat-value" style="color:#dc2626;">{{ $totalRejected }} <span style="font-size:0.85rem;font-weight:600;color:#94a3b8;">Kasbon</span></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#fef2f2,#fee2e2);color:#dc2626;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="tbl-header">
            <div>
                <div class="panel-title">Daftar Pengajuan Kasbon</div>
                <div class="panel-subtitle">
                    @if($kasbons->total() > 0)
                        Menampilkan {{ $kasbons->firstItem() }}-{{ $kasbons->lastItem() }} dari {{ $kasbons->total() }} pengajuan
                    @else
                        Belum ada pengajuan kasbon
                    @endif
                </div>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Keterangan</th>
                        <th class="r">Nominal (Rp)</th>
                        <th class="c">Status</th>
                        <th class="c">Jadwal Potong</th>
                        <th class="c" style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasbons as $kb)
                    <tr style="{{ $kb->status === 'pending' ? 'background:#fffbeb;' : '' }}">
                        <td>
                            <div class="td-main">{{ $kb->user?->name ?? 'User Dihapus' }}</div>
                            <div class="td-sub">{{ $kb->user?->employee?->position ?? 'Karyawan' }}</div>
                        </td>
                        <td>
                            <div class="td-main">{{ $kb->date->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="td-sub" title="{{ $kb->purpose }}" style="max-width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $kb->purpose }}
                            </div>
                        </td>
                        <td class="r" style="font-weight:700;">Rp {{ number_format($kb->amount, 0, ',', '.') }}</td>
                        <td class="c">
                            @if($kb->status === 'approved')
                                <span class="badge badge-success">Disetujui</span>
                                <div class="td-sub" style="margin-top:2px;">oleh {{ explode(' ', $kb->approver?->name ?? '')[0] }}</div>
                            @elseif($kb->status === 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                        <td class="c">
                            @if($kb->deduction_month)
                                <span class="badge badge-blue">{{ \Carbon\Carbon::createFromFormat('Y-m', $kb->deduction_month)->translatedFormat('F Y') }}</span>
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </td>
                        <td class="c">
                            @if($kb->status === 'pending')
                                <button class="btn-primary btn-sm" style="padding:0.3rem 0.65rem;font-size:0.7rem;border-radius:6px;"
                                        onclick="openApproveModal({{ $kb->id }}, '{{ $kb->user?->name ?? 'Karyawan' }}', {{ $kb->amount }})">Setujui</button>
                                <form action="{{ route('sdm.kasbon.reject', $kb) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tolak kasbon ini?');">
                                    @csrf
                                    <button type="submit" class="btn-danger btn-sm" style="padding:0.3rem 0.65rem;font-size:0.7rem;border-radius:6px;">Tolak</button>
                                </form>
                            @else
                                <span style="color:#94a3b8;font-size:0.8rem;">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                </div>
                                <div class="empty-state-title">Belum ada pengajuan</div>
                                <div class="empty-state-desc">Daftar kasbon karyawan masih kosong.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kasbons->hasPages())
            <div style="padding:1rem 1.375rem;border-top:1px solid #f1f5f9;">
                {{ $kasbons->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Approve Modal --}}
<div id="modalApprove" class="kb-modal" style="display:none;">
    <div class="kb-modal-box">
        <h3>Persetujuan Kasbon</h3>
        <p>Tentukan di bulan apa kasbon ini akan dipotong otomatis dari gaji <strong><span id="approveName"></span></strong>.</p>
        <form id="formApprove" action="" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Bulan Pemotongan Gaji <span class="required">*</span></label>
                <input type="month" name="deduction_month" class="form-input" required value="{{ now()->format('Y-m') }}" style="font-size:0.9375rem;">
                <div class="form-hint">Nominal Rp <span id="approveAmount"></span> akan otomatis masuk ke daftar Potongan Gaji bulan tersebut.</div>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:1.5rem;">
                <button type="button" class="btn-secondary" onclick="closeApproveModal()">Batal</button>
                <button type="submit" class="btn-primary">Setujui & Jadwalkan Potong</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.kb-modal {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
}
.kb-modal-box {
    background: #fff;
    width: 100%;
    max-width: 500px;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    animation: modIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    margin: 1rem;
}
@keyframes modIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
.kb-modal-box h3 {
    font-size: 1.4rem;
    font-weight: 800;
    margin: 0 0 0.5rem;
    color: #0f172a;
}
.kb-modal-box p {
    font-size: 0.9rem;
    color: #64748b;
    margin: 0 0 1.5rem;
}
</style>
@endpush

@push('scripts')
<script>
    function openApproveModal(id, name, amount) {
        document.getElementById('approveName').textContent = name;
        document.getElementById('approveAmount').textContent = amount.toLocaleString('id-ID');
        document.getElementById('formApprove').action = '{{ url('sdm/kasbon') }}/' + id + '/approve';
        document.getElementById('modalApprove').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeApproveModal() {
        document.getElementById('modalApprove').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modalApprove');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeApproveModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') closeApproveModal();
        });
    });
</script>
@endpush
@endsection
