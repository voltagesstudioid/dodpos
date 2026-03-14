<x-app-layout>
    <x-slot name="header">Detail Setoran: {{ $deposit->deposit_number }}</x-slot>

    <div class="page-container" style="max-width:760px;">
        <div style="margin-bottom:1rem; display:flex; gap:0.5rem;">
            <a href="{{ route('pasgar.setoran.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        @if(session('success'))<div class="alert alert-success">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert alert-danger">❌ {{ session('error') }}</div>@endif

        <div class="card">
            {{-- Header --}}
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">💰 {{ $deposit->deposit_number }}</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">{{ $deposit->deposit_date->format('d M Y') }}</p>
                </div>
                <span class="{{ $deposit->status_color }}" style="font-size:0.85rem; padding:0.375rem 0.875rem;">
                    {{ $deposit->status_label }}
                </span>
            </div>

            {{-- Info --}}
            <div style="padding:1.5rem; display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; border-bottom:1px solid #e2e8f0;">
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Anggota</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $deposit->member->user->name }}</div>
                    <div style="font-size:0.8rem; color:#64748b;">{{ $deposit->member->area ?? '—' }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Tanggal Setoran</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $deposit->deposit_date->format('d M Y') }}</div>
                </div>
                @if($deposit->verified_by)
                <div>
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Diverifikasi Oleh</div>
                    <div style="font-weight:600; color:#1e293b;">{{ $deposit->verifier?->name ?? '—' }}</div>
                    <div style="font-size:0.8rem; color:#64748b;">{{ $deposit->verified_at?->format('d M Y H:i') }}</div>
                </div>
                @endif
                @if($deposit->notes)
                <div style="grid-column:1/-1;">
                    <div style="font-size:0.75rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:0.375rem;">Catatan</div>
                    <div style="color:#475569;">{{ $deposit->notes }}</div>
                </div>
                @endif
            </div>

            {{-- Financial Summary --}}
            <div style="padding:1.5rem; border-bottom:1px solid #e2e8f0;">
                <div style="font-size:0.85rem; font-weight:700; color:#1e293b; margin-bottom:1rem;">📊 Rincian Keuangan</div>
                <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem;">
                    <div style="background:#eff6ff; border-radius:10px; padding:1rem;">
                        <div style="font-size:0.75rem; color:#3b82f6; font-weight:600; margin-bottom:0.25rem;">Penjualan Kanvas</div>
                        <div style="font-size:1.25rem; font-weight:800; color:#1e40af;">Rp {{ number_format($deposit->sales_amount, 0, ',', '.') }}</div>
                    </div>
                    <div style="background:#f0fdf4; border-radius:10px; padding:1rem;">
                        <div style="font-size:0.75rem; color:#10b981; font-weight:600; margin-bottom:0.25rem;">Penagihan Piutang</div>
                        <div style="font-size:1.25rem; font-weight:800; color:#065f46;">Rp {{ number_format($deposit->collection_amount, 0, ',', '.') }}</div>
                    </div>
                    <div style="background:#fef2f2; border-radius:10px; padding:1rem;">
                        <div style="font-size:0.75rem; color:#ef4444; font-weight:600; margin-bottom:0.25rem;">Pengeluaran Operasional</div>
                        <div style="font-size:1.25rem; font-weight:800; color:#991b1b;">Rp {{ number_format($deposit->expense_amount, 0, ',', '.') }}</div>
                    </div>
                    <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed); border-radius:10px; padding:1rem;">
                        <div style="font-size:0.75rem; color:#c7d2fe; font-weight:600; margin-bottom:0.25rem;">TOTAL DISETOR</div>
                        <div style="font-size:1.35rem; font-weight:800; color:white;">Rp {{ number_format($deposit->total_amount, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @if($deposit->status === 'pending')
            <div style="padding:1.25rem 1.5rem; background:#fffbeb; border-top:1px solid #fde68a;">
                <div style="font-size:0.85rem; font-weight:700; color:#92400e; margin-bottom:0.75rem;">⚡ Aksi Verifikasi</div>
                <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                    <form method="POST" action="{{ route('pasgar.setoran.verify', $deposit) }}" onsubmit="return confirm('Verifikasi setoran ini?')">
                        @csrf
                        <input type="hidden" name="action" value="verified">
                        <button type="submit" class="btn-primary" style="background:#10b981; border-color:#10b981;">✅ Verifikasi & Terima</button>
                    </form>
                    <form method="POST" action="{{ route('pasgar.setoran.verify', $deposit) }}" onsubmit="return confirm('Tolak setoran ini?')">
                        @csrf
                        <input type="hidden" name="action" value="rejected">
                        <button type="submit" class="btn-danger">❌ Tolak Setoran</button>
                    </form>
                </div>
            </div>
            @elseif($deposit->status === 'verified')
            <div style="padding:1.25rem 1.5rem; background:#f0fdf4; border-top:1px solid #bbf7d0;">
                <div style="color:#166534; font-size:0.875rem; font-weight:600;">✅ Setoran ini telah diverifikasi dan diterima oleh {{ $deposit->verifier?->name ?? 'Admin' }}.</div>
            </div>
            @elseif($deposit->status === 'rejected')
            <div style="padding:1.25rem 1.5rem; background:#fef2f2; border-top:1px solid #fecaca;">
                <div style="color:#991b1b; font-size:0.875rem; font-weight:600;">❌ Setoran ini telah ditolak.</div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
