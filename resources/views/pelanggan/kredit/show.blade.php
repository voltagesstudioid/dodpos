<x-app-layout>
    <x-slot name="header">Detail Kredit #{{ $kredit->credit_number }}</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        <div style="display:grid; grid-template-columns:1fr 320px; gap:1.5rem; align-items:start;">

            {{-- LEFT: Detail --}}
            <div>
                <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:1.25rem;">
                        <div>
                            <div style="font-size:1.25rem;font-weight:800;color:#1e293b;">{{ $kredit->credit_number }}</div>
                            <div style="font-size:0.8rem;color:#64748b;margin-top:0.25rem;">{{ $kredit->type_label }}</div>
                        </div>
                        <div style="display:flex;gap:0.5rem;">
                            {!! $kredit->status_badge !!}
                            @if($kredit->isOverdue()) <span class="badge-danger">⚠️ Jatuh Tempo!</span> @endif
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:1.5rem;">
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Pelanggan</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">
                                <a href="{{ route('pelanggan.show', $kredit->customer) }}" style="color:#4f46e5;">{{ $kredit->customer->name }}</a>
                            </div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Tgl. Transaksi</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $kredit->transaction_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;font-weight:600;">Jatuh Tempo</div>
                            <div style="font-weight:600;color:{{ $kredit->isOverdue() ? '#ef4444' : '#1e293b' }};margin-top:0.25rem;">
                                {{ $kredit->due_date ? $kredit->due_date->format('d M Y') : '-' }}
                            </div>
                        </div>
                    </div>

                    @if($kredit->description)
                    <div style="padding:0.75rem 1rem;background:#f8fafc;border-radius:8px;border-left:3px solid #6366f1;margin-bottom:1rem;">
                        <div style="font-size:0.7rem;font-weight:700;color:#4338ca;margin-bottom:0.2rem;">KETERANGAN</div>
                        <div style="font-size:0.875rem;">{{ $kredit->description }}</div>
                    </div>
                    @endif

                    {{-- Progress --}}
                    @php $pct = $kredit->amount > 0 ? min(100, ($kredit->paid_amount / $kredit->amount) * 100) : 0; @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#64748b;margin-bottom:0.5rem;">
                            <span>Progress Pembayaran</span><span>{{ number_format($pct, 1) }}%</span>
                        </div>
                        <div style="background:#f1f5f9;border-radius:999px;height:10px;">
                            <div style="width:{{ $pct }}%;background:{{ $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444') }};height:10px;border-radius:999px;transition:width 0.4s;"></div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-top:1rem;text-align:center;">
                            <div style="padding:0.75rem;background:#f8fafc;border-radius:8px;">
                                <div style="font-size:0.7rem;color:#64748b;">Total</div>
                                <div style="font-weight:700;color:#1e293b;">Rp {{ number_format($kredit->amount, 0, ',', '.') }}</div>
                            </div>
                            <div style="padding:0.75rem;background:#f0fdf4;border-radius:8px;">
                                <div style="font-size:0.7rem;color:#64748b;">Terbayar</div>
                                <div style="font-weight:700;color:#16a34a;">Rp {{ number_format($kredit->paid_amount, 0, ',', '.') }}</div>
                            </div>
                            <div style="padding:0.75rem;background:#fef2f2;border-radius:8px;">
                                <div style="font-size:0.7rem;color:#64748b;">Sisa</div>
                                <div style="font-weight:700;color:#ef4444;">Rp {{ number_format($kredit->remaining_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment history --}}
                <div class="card">
                    <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;font-weight:700;color:#1e293b;">💳 Riwayat Pembayaran</div>
                    @if($kredit->payments->isEmpty())
                        <div style="padding:2rem;text-align:center;color:#94a3b8;">Belum ada pembayaran.</div>
                    @else
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr><th>Tanggal</th><th>Jumlah</th><th>Metode</th><th>Referensi</th><th>Catatan</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                @foreach($kredit->payments as $p)
                                <tr>
                                    <td>{{ $p->payment_date->format('d M Y') }}</td>
                                    <td style="font-weight:700;color:#16a34a;">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td>{{ $p->payment_method_label }}</td>
                                    <td style="font-size:0.8rem;color:#64748b;">{{ $p->reference_number ?: '-' }}</td>
                                    <td style="font-size:0.8rem;color:#64748b;">{{ $p->notes ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('print.customer_credit_payment', $p->id) }}" target="_blank" class="btn-primary btn-sm" style="background:#3b82f6;">🖨️ Cetak</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            {{-- RIGHT: Payment Form --}}
            <div>
                @if($kredit->status !== 'paid')
                <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.9rem;">
                        {{ $kredit->type === 'debt' ? '💵 Catat Pembayaran Hutang' : '💰 Cairkan Piutang' }}
                    </div>
                    <form action="{{ route('pelanggan.kredit.pay', $kredit) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="payment_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah (Rp)</label>
                            <input type="number" name="amount" class="form-input" min="1"
                                   max="{{ $kredit->remaining_amount }}"
                                   placeholder="Sisa: Rp {{ number_format($kredit->remaining_amount, 0, ',', '.') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Metode Bayar</label>
                            <select name="payment_method" class="form-input" required>
                                <option value="cash">💵 Tunai</option>
                                <option value="transfer">🏦 Transfer</option>
                                <option value="qris">📱 QRIS</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Referensi</label>
                            <input type="text" name="reference_number" class="form-input" placeholder="No. transfer...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-input" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">💾 Simpan Pembayaran</button>
                    </form>
                </div>
                @else
                <div class="card" style="padding:1.5rem;margin-bottom:1rem;text-align:center;">
                    <div style="font-size:2.5rem;margin-bottom:0.5rem;">✅</div>
                    <div style="font-weight:700;color:#166534;font-size:1rem;">Sudah Lunas!</div>
                </div>
                @endif

                @if($kredit->status === 'unpaid')
                <div class="card" style="padding:1rem 1.5rem;margin-bottom:0.75rem;">
                    <form action="{{ route('pelanggan.kredit.destroy', $kredit) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-danger" style="width:100%;justify-content:center;">🗑️ Hapus Catatan</button>
                    </form>
                </div>
                @endif

                <a href="{{ route('pelanggan.kredit.index') }}" class="btn-secondary" style="width:100%;justify-content:center;padding:0.625rem;display:flex;">← Kembali</a>
            </div>
        </div>
    </div>
</x-app-layout>
