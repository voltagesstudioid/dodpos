<x-app-layout>
    <x-slot name="header">Detail Hutang - {{ $customer->name }}</x-slot>

    <div class="page-container">
        @if(session('success')) <div class="alert alert-success">✅ {{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">❌ {{ session('error') }}</div>   @endif

        {{-- Header Info --}}
        <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
            <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:1rem;">
                <div style="display:flex; gap:1rem; align-items:center;">
                    <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg, #fee2e2, #fecaca);display:flex;align-items:center;justify-content:center;font-size:2rem;">👤</div>
                    <div>
                        <div style="font-size:1.5rem;font-weight:800;color:#1e293b;">{{ $customer->name }}</div>
                        <div style="font-size:0.85rem;color:#64748b;margin-top:0.25rem;">
                            📞 {{ $customer->phone ?? 'Tidak ada telepon' }} |
                            📍 {{ $customer->address ?? 'Tidak ada alamat' }}
                        </div>
                        @if($customer->category)
                            <span style="display:inline-block;margin-top:0.5rem;background:#e0e7ff;color:#4338ca;padding:0.25rem 0.75rem;border-radius:999px;font-size:0.75rem;font-weight:600;">
                                {{ ucfirst($customer->category) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:0.8rem;color:#64748b;">Total Hutang</div>
                    <div style="font-size:2rem;font-weight:800;color:#ef4444;">Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;margin-top:0.25rem;">{{ $totalTransactions }} transaksi belum lunas</div>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 360px; gap:1.5rem; align-items:start;">

            {{-- LEFT: Daftar Hutang --}}
            <div>
                <div class="card" style="margin-bottom:1rem;">
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; font-weight:700; color:#1e293b;">
                        📋 Rincian Hutang per Transaksi
                    </div>
                    <div style="padding:0;">
                        @forelse($debts as $debt)
                        @php $pct = $debt->amount > 0 ? ($debt->paid_amount / $debt->amount) * 100 : 0; @endphp
                        <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; {{ $loop->last ? 'border-bottom:none;' : '' }}">
                            <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:0.75rem;">
                                <div>
                                    <div style="font-weight:700;color:#1e293b;">{{ $debt->credit_number }}</div>
                                    <div style="font-size:0.75rem;color:#64748b;margin-top:0.25rem;">
                                        {{ $debt->transaction_date->format('d M Y') }}
                                        @if($debt->due_date) | Jatuh tempo: {{ $debt->due_date->format('d M Y') }} @endif
                                    </div>
                                </div>
                                <span style="background:{{ $debt->status === 'paid' ? '#dcfce7' : ($debt->status === 'partial' ? '#fef3c7' : '#fee2e2') }}; color:{{ $debt->status === 'paid' ? '#16a34a' : ($debt->status === 'partial' ? '#d97706' : '#dc2626') }}; padding:0.25rem 0.75rem; border-radius:999px; font-size:0.75rem; font-weight:600;">
                                    {{ $debt->status === 'paid' ? 'Lunas' : ($debt->status === 'partial' ? 'Sebagian' : 'Belum Bayar') }}
                                </span>
                            </div>

                            <div style="font-size:0.875rem;color:#64748b;margin-bottom:0.75rem;">{{ $debt->description }}</div>

                            <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:0.75rem; margin-bottom:0.75rem;">
                                <div style="background:#f8fafc;padding:0.5rem;border-radius:6px;text-align:center;">
                                    <div style="font-size:0.65rem;color:#94a3b8;">Total</div>
                                    <div style="font-weight:700;font-size:0.9rem;">Rp {{ number_format($debt->amount, 0, ',', '.') }}</div>
                                </div>
                                <div style="background:#f0fdf4;padding:0.5rem;border-radius:6px;text-align:center;">
                                    <div style="font-size:0.65rem;color:#94a3b8;">Terbayar</div>
                                    <div style="font-weight:700;font-size:0.9rem;color:#16a34a;">Rp {{ number_format($debt->paid_amount, 0, ',', '.') }}</div>
                                </div>
                                <div style="background:#fef2f2;padding:0.5rem;border-radius:6px;text-align:center;">
                                    <div style="font-size:0.65rem;color:#94a3b8;">Sisa</div>
                                    <div style="font-weight:700;font-size:0.9rem;color:#ef4444;">Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}</div>
                                </div>
                            </div>

                            <div style="background:#f1f5f9;border-radius:999px;height:6px;margin-bottom:0.25rem;">
                                <div style="width:{{ min(100, $pct) }}%;background:{{ $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444') }};height:6px;border-radius:999px;"></div>
                            </div>
                            <div style="font-size:0.7rem;color:#64748b;text-align:right;">{{ number_format($pct, 1) }}% terbayar</div>
                        </div>
                        @empty
                        <div style="padding:2rem; text-align:center; color:#94a3b8;">
                            <div style="font-size:2rem;margin-bottom:0.5rem;">🎉</div>
                            <div>Pelanggan ini tidak memiliki hutang</div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT: Form Pembayaran --}}
            <div>
                <div class="card" style="position:sticky; top:1rem;">
                    <div style="padding:1rem 1.25rem; border-bottom:1px solid #f1f5f9; background:linear-gradient(135deg, #dbeafe, #bfdbfe);">
                        <div style="font-weight:800;color:#1e40af;font-size:1.1rem;">💰 Form Pembayaran Hutang</div>
                        <div style="font-size:0.8rem;color:#3b82f6;margin-top:0.25rem;">Total Hutang: Rp {{ number_format($totalDebt, 0, ',', '.') }}</div>
                    </div>

                    @if($totalDebt > 0)
                    <div style="padding:1.25rem;">
                        <form action="{{ route('pelanggan.kredit.pay_consolidated', $customer) }}" method="POST">
                            @csrf

                            <div style="margin-bottom:1rem;">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.4rem;">Jumlah Pembayaran (Rp) *</label>
                                <input type="number" name="amount" value="{{ old('amount', $totalDebt) }}" min="1" max="{{ $totalDebt }}" required
                                    class="form-input" style="font-size:1.1rem;font-weight:700;">
                                <div style="font-size:0.75rem;color:#6b7280;margin-top:0.25rem;">
                                    Maksimal: Rp {{ number_format($totalDebt, 0, ',', '.') }}
                                </div>
                                @error('amount')
                                <div style="font-size:0.75rem;color:#dc2626;margin-top:0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-bottom:1rem;">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.4rem;">Tanggal Pembayaran *</label>
                                <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" required class="form-input">
                                @error('payment_date')
                                <div style="font-size:0.75rem;color:#dc2626;margin-top:0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-bottom:1rem;">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.4rem;">Metode Pembayaran *</label>
                                <select name="payment_method" required class="form-input">
                                    <option value="cash" @selected(old('payment_method')=='cash')>💵 Tunai (Cash)</option>
                                    <option value="transfer" @selected(old('payment_method')=='transfer')>🏦 Transfer Bank</option>
                                    <option value="qris" @selected(old('payment_method')=='qris')>📱 QRIS</option>
                                    <option value="other" @selected(old('payment_method')=='other')>📝 Lainnya</option>
                                </select>
                                @error('payment_method')
                                <div style="font-size:0.75rem;color:#dc2626;margin-top:0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-bottom:1rem;">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.4rem;">No. Referensi (opsional)</label>
                                <input type="text" name="reference_number" value="{{ old('reference_number') }}" class="form-input" placeholder="No. Transfer/Invoice">
                                @error('reference_number')
                                <div style="font-size:0.75rem;color:#dc2626;margin-top:0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div style="margin-bottom:1.25rem;">
                                <label style="display:block;font-size:0.8rem;font-weight:600;color:#374151;margin-bottom:0.4rem;">Catatan (opsional)</label>
                                <textarea name="notes" rows="2" class="form-input" placeholder="Keterangan pembayaran...">{{ old('notes') }}</textarea>
                                @error('notes')
                                <div style="font-size:0.75rem;color:#dc2626;margin-top:0.25rem;">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn-primary" style="width:100%;padding:0.875rem;font-size:1rem;font-weight:700;">
                                💰 Bayar Hutang
                            </button>

                            <div style="margin-top:1rem;padding:0.75rem;background:#f0fdf4;border-radius:8px;border-left:3px solid #22c55e;">
                                <div style="font-size:0.75rem;color:#15803d;font-weight:600;margin-bottom:0.25rem;">💡 Info Pembayaran</div>
                                <div style="font-size:0.75rem;color:#166534;">
                                    Pembayaran akan otomatis mengurangi hutang dari transaksi terlama (FIFO). Sisa pembayaran akan dialokasikan ke hutang berikutnya.
                                </div>
                            </div>
                        </form>
                    </div>
                    @else
                    <div style="padding:2rem; text-align:center;">
                        <div style="font-size:3rem;margin-bottom:1rem;">🎉</div>
                        <div style="font-weight:700;color:#16a34a;margin-bottom:0.5rem;">Tidak Ada Hutang</div>
                        <div style="font-size:0.85rem;color:#64748b;">Pelanggan ini sudah lunas semua hutangnya.</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
