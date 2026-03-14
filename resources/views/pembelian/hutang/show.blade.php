<x-app-layout>
    <x-slot name="header">Detail Hutang #{{ $hutang->invoice_number }}</x-slot>

    <div class="page-container">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

            {{-- LEFT --}}
            <div>
                {{-- Header Info --}}
                <div class="card" style="padding:1.5rem; margin-bottom:1.25rem;">
                    <div style="display:flex; justify-content:space-between; align-items:start; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
                        <div>
                            <div style="font-size:1.25rem;font-weight:800;color:#1e293b;">{{ $hutang->invoice_number }}</div>
                            <div style="font-size:0.8rem;color:#64748b;margin-top:0.2rem;">{{ $hutang->supplier->name }}</div>
                        </div>
                        {!! $hutang->status_badge !!}
                        @if($hutang->isOverdue()) <span class="badge-danger">⚠️ Jatuh Tempo!</span> @endif
                    </div>

                    <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1rem;">
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;">Tgl. Transaksi</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $hutang->transaction_date->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;">Jatuh Tempo</div>
                            @if($hutang->isOverdue())
                            <div style="font-weight:600;color:#ef4444;margin-top:0.25rem;">
                            @else
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">
                            @endif
                                {{ $hutang->due_date ? $hutang->due_date->format('d M Y') : '-' }}
                            </div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;">Referensi PO</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $hutang->purchaseOrder ? $hutang->purchaseOrder->po_number : '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;">Dibuat</div>
                            <div style="font-weight:600;color:#1e293b;margin-top:0.25rem;">{{ $hutang->created_at->format('d M Y') }}</div>
                        </div>
                    </div>

                    {{-- Progress bar --}}
                    <div style="margin-top:1.5rem;">
                        @php
                            $pct = $hutang->total_amount > 0 ? min(100, ($hutang->paid_amount / $hutang->total_amount) * 100) : 0;
                            $pctInt = (int) round($pct);
                            $barColor = $pctInt >= 100 ? '#10b981' : ($pctInt > 0 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#64748b;margin-bottom:0.5rem;">
                            <span>Progress Pembayaran</span><span>{{ number_format($pct, 1) }}%</span>
                        </div>
                        <div style="background:#f1f5f9;border-radius:999px;height:10px;">
                            <div class="progress-fill" data-pct="{{ $pctInt }}" data-color="{{ $barColor }}" style="width:0%;height:10px;border-radius:999px;transition:width 0.4s;"></div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1rem;margin-top:1rem;text-align:center;">
                            <div style="padding:0.75rem;background:#f8fafc;border-radius:8px;">
                                <div style="font-size:0.7rem;color:#64748b;">Total</div>
                                <div style="font-weight:700;color:#1e293b;">Rp {{ number_format($hutang->total_amount, 0, ',', '.') }}</div>
                            </div>
                            <div style="padding:0.75rem;background:#f0fdf4;border-radius:8px;">
                                <div style="font-size:0.7rem;color:#64748b;">Terbayar</div>
                                <div style="font-weight:700;color:#16a34a;">Rp {{ number_format($hutang->paid_amount, 0, ',', '.') }}</div>
                            </div>
                            <div style="padding:0.75rem;background:#fef2f2;border-radius:8px;">
                                <div style="font-size:0.7rem;color:#64748b;">Sisa</div>
                                <div style="font-weight:700;color:#ef4444;">Rp {{ number_format($hutang->remaining_amount, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>

                    @if($hutang->notes)
                    <div style="margin-top:1rem;font-size:0.85rem;color:#64748b;">📝 {{ $hutang->notes }}</div>
                    @endif
                </div>

                {{-- Payment History --}}
                <div class="card">
                    <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;font-weight:700;color:#1e293b;">💳 Riwayat Pembayaran</div>
                    @if($hutang->payments->isEmpty())
                        <div style="padding:2rem;text-align:center;color:#94a3b8;">Belum ada pembayaran.</div>
                    @else
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>No. Referensi</th>
                                    <th>Catatan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hutang->payments as $p)
                                <tr>
                                    <td>{{ $p->payment_date->format('d M Y') }}</td>
                                    <td style="font-weight:700;color:#16a34a;">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td>{{ $p->payment_method_label }}</td>
                                    <td style="font-size:0.8rem;color:#64748b;">{{ $p->reference_number ?: '-' }}</td>
                                    <td style="font-size:0.8rem;color:#64748b;">{{ $p->notes ?: '-' }}</td>
                                    <td>
                                        <a href="{{ route('print.supplier_payment', $p->id) }}" target="_blank" class="btn-primary btn-sm" style="background:#3b82f6;">🖨️ Cetak</a>
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
            <div id="bayar">
                @if($hutang->status !== 'paid')
                @can('edit_hutang_supplier')
                <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:1rem;font-size:0.9rem;">💵 Catat Pembayaran</div>
                    <form action="{{ route('pembelian.hutang.pay', $hutang) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="payment_date" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Bayar (Rp)</label>
                            <input type="number" name="amount" class="form-input" min="1"
                                   max="{{ $hutang->remaining_amount }}"
                                   placeholder="Sisa: Rp {{ number_format($hutang->remaining_amount, 0, ',', '.') }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-input" required>
                                <option value="cash">💵 Tunai</option>
                                <option value="transfer">🏦 Transfer Bank</option>
                                <option value="check">📄 Cek / Giro</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Referensi</label>
                            <input type="text" name="reference_number" class="form-input" placeholder="No. transfer / cek...">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Catatan</label>
                            <textarea name="notes" class="form-input" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:0.75rem;">💾 Simpan Pembayaran</button>
                    </form>
                </div>
                @endcan
                @else
                <div class="card" style="padding:1.5rem; margin-bottom:1rem; text-align:center;">
                    <div style="font-size:2rem;margin-bottom:0.5rem;">✅</div>
                    <div style="font-weight:700;color:#166534;font-size:1rem;">Hutang Lunas!</div>
                    <div style="font-size:0.8rem;color:#64748b;margin-top:0.25rem;">Semua pembayaran telah selesai.</div>
                </div>
                @endif

                <a href="{{ route('pembelian.hutang.index') }}" class="btn-secondary" style="width:100%;justify-content:center;padding:0.625rem;display:flex;">← Kembali</a>
            </div>
        </div>
    </div>
    <script>
        (function () {
            const el = document.querySelector('.progress-fill');
            if (!el) return;
            const pct = Math.max(0, Math.min(100, parseInt(el.dataset.pct || '0', 10) || 0));
            el.style.width = pct + '%';
            if (el.dataset.color) el.style.backgroundColor = el.dataset.color;
        })();
    </script>
</x-app-layout>
