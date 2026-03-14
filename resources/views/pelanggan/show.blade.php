<x-app-layout>
    <x-slot name="header">Detail Pelanggan — {{ $pelanggan->name }}</x-slot>
    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div style="display:grid; grid-template-columns:1fr 280px; gap:1.5rem; align-items:start;">

            {{-- LEFT: Info + Kredit list --}}
            <div>
                <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1.25rem;">
                        <div>
                            <div style="font-size:1.25rem;font-weight:800;color:#1e293b;">{{ $pelanggan->name }}</div>
                            <div style="font-size:0.8rem;color:#64748b;">📱 {{ $pelanggan->phone ?: '-' }} &nbsp;|&nbsp; ✉️ {{ $pelanggan->email ?: '-' }}</div>
                        </div>
                        @if($pelanggan->is_active)
                            <span class="badge-success">Aktif</span>
                        @else
                            <span class="badge-danger">Nonaktif</span>
                        @endif
                    </div>

                    @if($pelanggan->address)
                    <div style="font-size:0.85rem;color:#64748b;margin-bottom:1rem;">📍 {{ $pelanggan->address }}</div>
                    @endif

                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;">
                        <div style="padding:0.875rem;background:#fef2f2;border-radius:10px;text-align:center;">
                            <div style="font-size:0.7rem;color:#94a3b8;margin-bottom:0.25rem;">Hutang Aktif</div>
                            <div style="font-size:1.1rem;font-weight:800;color:#ef4444;">Rp {{ number_format($pelanggan->current_debt, 0, ',', '.') }}</div>
                        </div>
                        <div style="padding:0.875rem;background:#eff6ff;border-radius:10px;text-align:center;">
                            <div style="font-size:0.7rem;color:#94a3b8;margin-bottom:0.25rem;">Limit Kredit</div>
                            <div style="font-size:1.1rem;font-weight:800;color:#3b82f6;">Rp {{ number_format($pelanggan->credit_limit, 0, ',', '.') }}</div>
                        </div>
                        <div style="padding:0.875rem;background:#f0fdf4;border-radius:10px;text-align:center;">
                            <div style="font-size:0.7rem;color:#94a3b8;margin-bottom:0.25rem;">Sisa Limit</div>
                            <div style="font-size:1.1rem;font-weight:800;color:#16a34a;">Rp {{ number_format($pelanggan->remaining_credit_limit, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Active debts --}}
                @if($activeDebts->count() > 0)
                <div class="card" style="margin-bottom:1rem;">
                    <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;font-weight:700;color:#ef4444;font-size:0.875rem;">⚠️ Hutang Belum Lunas</div>
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>No.</th><th>Keterangan</th><th>Jatuh Tempo</th><th>Sisa</th><th></th></tr></thead>
                            <tbody>
                                @foreach($activeDebts as $d)
                                <tr @if($d->isOverdue()) style="background:#fff7f7;" @endif>
                                    <td style="font-size:0.8rem;font-weight:600;">{{ $d->credit_number }}</td>
                                    <td style="font-size:0.8rem;">{{ $d->description }}</td>
                                    <td style="font-size:0.8rem;color:{{ $d->isOverdue() ? '#ef4444' : '#64748b' }}">{{ $d->due_date ? $d->due_date->format('d/m/Y') : '-' }}</td>
                                    <td style="font-weight:700;color:#ef4444;font-size:0.85rem;">Rp {{ number_format($d->remaining_amount, 0, ',', '.') }}</td>
                                    <td><a href="{{ route('pelanggan.kredit.show', $d) }}" class="btn-primary btn-sm">Bayar</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Recent history --}}
                <div class="card">
                    <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;font-weight:700;color:#1e293b;font-size:0.875rem;">📋 Riwayat Kredit Terakhir</div>
                    @if($recentCredits->isEmpty())
                        <div style="padding:2rem;text-align:center;color:#94a3b8;">Belum ada riwayat kredit.</div>
                    @else
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead><tr><th>No.</th><th>Jenis</th><th>Keterangan</th><th>Jumlah</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach($recentCredits as $rc)
                                <tr>
                                    <td><a href="{{ route('pelanggan.kredit.show', $rc) }}" style="font-weight:600;font-size:0.8rem;color:#4f46e5;">{{ $rc->credit_number }}</a></td>
                                    <td>
                                        <span style="background:{{ $rc->type === 'debt' ? '#fee2e2' : '#dcfce7' }};color:{{ $rc->type === 'debt' ? '#dc2626' : '#16a34a' }};padding:0.1rem 0.4rem;border-radius:999px;font-size:0.65rem;font-weight:600;">
                                            {{ $rc->type === 'debt' ? 'Hutang' : 'Piutang' }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.8rem;">{{ Str::limit($rc->description, 40) }}</td>
                                    <td style="font-weight:600;font-size:0.85rem;">Rp {{ number_format($rc->amount, 0, ',', '.') }}</td>
                                    <td>{!! $rc->status_badge !!}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Actions --}}
            <div>
                <div class="card" style="padding:1.25rem; margin-bottom:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:0.875rem;font-size:0.875rem;">⚡ Aksi Cepat</div>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt', 'customer_id'=>$pelanggan->id]) }}" class="btn-primary" style="width:100%;justify-content:center;margin-bottom:0.5rem;">+ Catat Hutang</a>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit', 'customer_id'=>$pelanggan->id]) }}" class="btn-secondary" style="width:100%;justify-content:center;margin-bottom:0.5rem;">+ Catat Piutang</a>
                    <a href="{{ route('pelanggan.edit', $pelanggan) }}" class="btn-secondary" style="width:100%;justify-content:center;">✏️ Edit Data</a>
                </div>

                @if($pelanggan->notes)
                <div class="card" style="padding:1.25rem;">
                    <div style="font-size:0.75rem;font-weight:700;color:#94a3b8;margin-bottom:0.5rem;">CATATAN</div>
                    <div style="font-size:0.875rem;color:#475569;">{{ $pelanggan->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
