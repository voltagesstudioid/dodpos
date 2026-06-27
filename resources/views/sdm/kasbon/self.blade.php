@extends('layouts.app')

@section('header', 'Kasbon Saya')

@section('content')
<div class="page-container animate-in">
    <div class="ph">
        <div class="ph-left">
            <div class="ph-icon indigo">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <h1 class="ph-title">Kasbon (Cash Advance)</h1>
                <div class="ph-subtitle">Ajukan pinjaman yang akan dipotong otomatis dari gaji bulan berikutnya.</div>
            </div>
        </div>
        <div class="ph-actions">
            <button class="btn-primary" onclick="openAjukanModal()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Ajukan Kasbon
            </button>
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
    @if($errors->any())
        <div class="alert alert-danger animate-in">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>Terdapat {{ $errors->count() }} kesalahan. Silakan periksa kembali.</span>
        </div>
    @endif

    <div class="stat-grid">
        <div class="stat-card animate-in animate-in-delay-1">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Total Pengajuan</div>
                    <div class="stat-value" style="color:#0f172a;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#f1f5f9,#e2e8f0);color:#64748b;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
        </div>
        <div class="stat-card animate-in animate-in-delay-2">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Disetujui</div>
                    <div class="stat-value" style="color:#059669;">Rp {{ number_format($totalApproved, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#059669;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
            </div>
        </div>
        <div class="stat-card animate-in animate-in-delay-3">
            <div class="stat-card-row">
                <div>
                    <div class="stat-label">Menunggu</div>
                    <div class="stat-value" style="color:#d97706;">{{ $totalPending }} <span style="font-size:0.85rem;font-weight:600;color:#94a3b8;">Pengajuan</span></div>
                </div>
                <div class="stat-icon" style="background:linear-gradient(135deg,#fffbeb,#fef3c7);color:#d97706;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="tbl-header">
            <div>
                <div class="panel-title">Riwayat Kasbon Saya</div>
                <div class="panel-subtitle">
                    @if($kasbons->total() > 0)
                        Menampilkan {{ $kasbons->firstItem() }}-{{ $kasbons->lastItem() }} dari {{ $kasbons->total() }} pengajuan
                    @else
                        Belum ada riwayat kasbon
                    @endif
                </div>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Tujuan / Keterangan</th>
                        <th class="r">Nominal (Rp)</th>
                        <th class="c">Status</th>
                        <th class="c">Potong Gaji Bulan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasbons as $kb)
                    <tr>
                        <td>
                            <div class="td-main">{{ $kb->date->format('d M Y') }}</div>
                        </td>
                        <td>
                            <div class="td-sub" title="{{ $kb->purpose }}" style="max-width:300px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                {{ $kb->purpose }}
                            </div>
                        </td>
                        <td class="r" style="font-weight:700;">Rp {{ number_format($kb->amount, 0, ',', '.') }}</td>
                        <td class="c">
                            @if($kb->status === 'approved')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($kb->status === 'rejected')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Menunggu</span>
                            @endif
                        </td>
                        <td class="c">
                            @if($kb->deduction_month)
                                <span class="badge badge-blue">{{ \Carbon\Carbon::createFromFormat('Y-m', $kb->deduction_month)->translatedFormat('F Y') }}</span>
                            @else
                                <span style="color:#cbd5e1;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                </div>
                                <div class="empty-state-title">Belum ada riwayat kasbon</div>
                                <div class="empty-state-desc">Anda belum pernah mengajukan kasbon.</div>
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

{{-- Modal Ajukan --}}
<div id="modalAjukan" class="kb-modal" style="display:none;">
    <div class="kb-modal-box">
        <h3>Ajukan Kasbon Baru</h3>
        <p>Pastikan nominal sesuai dengan kebutuhan mendesak Anda.</p>
        <form action="{{ route('sdm.kasbon.self_store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nominal (Rp) <span class="required">*</span></label>
                <div class="form-prefix">
                    <span class="form-prefix-text">Rp</span>
                    <input type="text" inputmode="numeric" data-currency name="amount" class="form-input" required placeholder="500.000">
                </div>
                @error('amount') <div class="form-error">{{ $message }}</div> @enderror
                <div class="form-hint">Minimal Rp 1.000, maksimal Rp 999.999.999</div>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan / Tujuan <span class="required">*</span></label>
                <textarea name="purpose" rows="3" class="form-input" required placeholder="Contoh: Biaya rumah sakit keluarga" style="resize:vertical;">{{ old('purpose') }}</textarea>
                @error('purpose') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div style="display:flex;justify-content:flex-end;gap:0.75rem;margin-top:1.5rem;">
                <button type="button" class="btn-secondary" onclick="closeAjukanModal()">Batal</button>
                <button type="submit" class="btn-primary">Kirim Pengajuan</button>
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
    function openAjukanModal() {
        document.getElementById('modalAjukan').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeAjukanModal() {
        document.getElementById('modalAjukan').style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('modalAjukan');
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeAjukanModal();
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') closeAjukanModal();
        });

        document.querySelectorAll('[data-currency]').forEach(function(el) {
            if (el.value) {
                el.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    });
</script>
@endpush
@endsection
