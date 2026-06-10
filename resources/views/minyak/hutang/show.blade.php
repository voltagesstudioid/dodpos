<x-app-layout>
    <x-slot name="header">Detail Hutang — {{ $hutang->pelanggan->nama_toko ?? 'N/A' }}</x-slot>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        .hs-page { max-width:68rem; margin:0 auto; padding:0 1rem 3rem; font-family:'Plus Jakarta Sans',sans-serif; }

        .hs-back { display:inline-flex; align-items:center; gap:0.5rem; font-size:0.8125rem; font-weight:600; color:#64748b; text-decoration:none; padding:0.5rem 0.75rem; border-radius:10px; transition:all 0.2s; margin-bottom:1.25rem; }
        .hs-back:hover { background:#f1f5f9; color:#334155; }

        .hs-grid { display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start; }

        /* Card */
        .hs-card { background:#fff; border:1px solid #e2e8f0; border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(0,0,0,0.04); margin-bottom:1.25rem; }
        .hs-card-hdr { padding:1.25rem 1.5rem; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:0.75rem; }
        .hs-card-hdr-ico { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .hs-card-hdr-title { font-size:0.9rem; font-weight:700; color:#1e293b; }
        .hs-card-body { padding:1.5rem; }

        /* Info Grid */
        .hs-info { display:grid; grid-template-columns:repeat(4,1fr); gap:1rem; }
        .hs-info-lbl { font-size:0.6875rem; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; margin-bottom:0.25rem; }
        .hs-info-val { font-size:0.875rem; font-weight:700; color:#1e293b; }
        .hs-info-val.danger { color:#dc2626; }

        /* Badge */
        .hs-badge { display:inline-flex; align-items:center; gap:0.3rem; padding:0.3rem 0.7rem; border-radius:999px; font-size:0.72rem; font-weight:700; }
        .hs-badge.unpaid { background:#fef3c7; color:#92400e; }
        .hs-badge.overdue { background:#fef2f2; color:#991b1b; }
        .hs-badge.paid { background:#d1fae5; color:#065f46; }

        /* Progress */
        .hs-progress-wrap { margin-top:1.5rem; }
        .hs-progress-labels { display:flex; justify-content:space-between; font-size:0.75rem; color:#64748b; margin-bottom:0.5rem; }
        .hs-progress-bar { background:#f1f5f9; border-radius:999px; height:12px; overflow:hidden; }
        .hs-progress-fill { height:100%; border-radius:999px; transition:width 0.6s ease; }
        .hs-progress-amounts { display:grid; grid-template-columns:repeat(3,1fr); gap:0.75rem; margin-top:1rem; }
        .hs-progress-amt { padding:0.875rem; border-radius:12px; text-align:center; }
        .hs-progress-amt-lbl { font-size:0.6875rem; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
        .hs-progress-amt-val { font-size:1.125rem; font-weight:800; margin-top:0.25rem; letter-spacing:-0.02em; }

        /* Table */
        .hs-table-wrap { overflow-x:auto; }
        .hs-table { width:100%; border-collapse:collapse; font-size:0.8125rem; }
        .hs-table th { text-align:left; padding:0.75rem 1rem; font-size:0.6875rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:#94a3b8; border-bottom:2px solid #f1f5f9; }
        .hs-table td { padding:0.875rem 1rem; border-bottom:1px solid #f8fafc; color:#334155; }
        .hs-table tr:last-child td { border-bottom:none; }
        .hs-table .amount { font-weight:700; color:#059669; }

        /* Form */
        .hs-form-group { margin-bottom:1rem; }
        .hs-form-label { display:block; font-size:0.8125rem; font-weight:600; color:#334155; margin-bottom:0.375rem; }
        .hs-form-input { width:100%; padding:0.625rem 0.875rem; border:1.5px solid #e2e8f0; border-radius:10px; font-size:0.8125rem; font-family:inherit; transition:all 0.2s; box-sizing:border-box; }
        .hs-form-input:focus { outline:none; border-color:#3b82f6; box-shadow:0 0 0 3px rgba(59,130,246,0.1); }
        select.hs-form-input { cursor:pointer; appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.75rem center; padding-right:2.25rem; }

        .hs-btn { display:flex; align-items:center; justify-content:center; gap:0.5rem; width:100%; padding:0.75rem 1rem; border:none; border-radius:12px; font-size:0.8125rem; font-weight:700; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .hs-btn-primary { background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff; box-shadow:0 4px 16px rgba(37,99,235,0.3); }
        .hs-btn-primary:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(37,99,235,0.4); }

        .hs-lunas-card { text-align:center; padding:2rem 1.5rem; }
        .hs-lunas-ico { font-size:3rem; margin-bottom:0.75rem; }
        .hs-lunas-title { font-size:1.125rem; font-weight:800; color:#065f46; }
        .hs-lunas-sub { font-size:0.8125rem; color:#64748b; margin-top:0.25rem; }

        .hs-empty { text-align:center; padding:2.5rem 1rem; color:#94a3b8; font-size:0.8125rem; }
        .hs-keterangan { font-size:0.8125rem; color:#64748b; padding:0.75rem 1rem; background:#f8fafc; border-radius:10px; margin-top:1rem; }

        /* Payment Status Badges */
        .hs-pay-badge { display:inline-flex; align-items:center; gap:0.25rem; padding:0.2rem 0.5rem; border-radius:999px; font-size:0.625rem; font-weight:700; }
        .hs-pay-badge.pending { background:#fef3c7; color:#92400e; }
        .hs-pay-badge.confirmed { background:#d1fae5; color:#065f46; }
        .hs-pay-badge.rejected { background:#fee2e2; color:#991b1b; }

        /* Pending Alert */
        .hs-pending-alert { background:#fffbeb; border:1.5px solid #fde68a; border-radius:12px; padding:0.875rem 1rem; margin-bottom:1rem; display:flex; align-items:center; gap:0.75rem; }
        .hs-pending-alert-ico { font-size:1.25rem; flex-shrink:0; }
        .hs-pending-alert-txt { font-size:0.75rem; color:#92400e; line-height:1.5; }
        .hs-pending-alert-txt strong { color:#78350f; }

        /* Confirm/Reject Buttons */
        .hs-pay-actions { display:flex; gap:0.375rem; margin-top:0.5rem; }
        .hs-pay-btn { display:inline-flex; align-items:center; gap:0.25rem; padding:0.3rem 0.625rem; border-radius:7px; font-size:0.6875rem; font-weight:600; border:1px solid; cursor:pointer; transition:all 0.2s; font-family:inherit; }
        .hs-pay-btn-confirm { background:#ecfdf5; color:#065f46; border-color:#a7f3d0; }
        .hs-pay-btn-confirm:hover { background:#d1fae5; border-color:#6ee7b7; }
        .hs-pay-btn-reject { background:#fef2f2; color:#991b1b; border-color:#fecaca; }
        .hs-pay-btn-reject:hover { background:#fee2e2; border-color:#f87171; }

        /* Reject Form */
        .hs-reject-form { display:none; margin-top:0.5rem; }
        .hs-reject-form.active { display:block; }
        .hs-reject-input { width:100%; padding:0.5rem 0.75rem; border:1.5px solid #fecaca; border-radius:8px; font-size:0.75rem; font-family:inherit; resize:none; }
        .hs-reject-input:focus { outline:none; border-color:#ef4444; box-shadow:0 0 0 3px rgba(239,68,68,0.1); }

        @media(max-width:768px) {
            .hs-grid { grid-template-columns:1fr; }
            .hs-info { grid-template-columns:repeat(2,1fr); }
            .hs-progress-amounts { grid-template-columns:1fr; }
        }
    </style>
    @endpush

    <div class="hs-page" style="padding-top:1.5rem;">
        <a href="{{ route('minyak.hutang.index') }}" class="hs-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 8H1M8 15l-7-7 7-7"/></svg>
            Kembali ke Daftar Hutang
        </a>

        <div class="hs-grid">
            <div>
                <div class="hs-card">
                    <div class="hs-card-hdr" style="background:linear-gradient(135deg,#fef2f2,#fff1f2);">
                        <div class="hs-card-hdr-ico" style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff;">💰</div>
                        <div style="flex:1;">
                            <div class="hs-card-hdr-title">{{ $hutang->pelanggan->nama_toko ?? 'N/A' }}</div>
                            <div style="font-size:0.75rem; color:#64748b;">{{ $hutang->pelanggan->nama_pemilik ?? '-' }}</div>
                        </div>
                        @if($hutang->status === 'lunas')
                            <span class="hs-badge paid">✅ Lunas</span>
                        @elseif($hutang->status === 'overdue' || ($hutang->status === 'belum_lunas' && $hutang->jatuh_tempo && $hutang->jatuh_tempo->isPast()))
                            <span class="hs-badge overdue">⚠️ Overdue</span>
                        @else
                            <span class="hs-badge unpaid">🕐 Belum Lunas</span>
                        @endif
                    </div>
                    <div class="hs-card-body">
                        <div class="hs-info">
                            <div>
                                <div class="hs-info-lbl">No. Faktur</div>
                                <div class="hs-info-val">{{ $hutang->penjualan->no_faktur ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="hs-info-lbl">Tanggal Jual</div>
                                <div class="hs-info-val">{{ $hutang->penjualan->tanggal_jual ? \Carbon\Carbon::parse($hutang->penjualan->tanggal_jual)->format('d M Y') : '-' }}</div>
                            </div>
                            <div>
                                <div class="hs-info-lbl">Jatuh Tempo</div>
                                <div class="hs-info-val {{ $hutang->jatuh_tempo && $hutang->jatuh_tempo->isPast() ? 'danger' : '' }}">
                                    {{ $hutang->jatuh_tempo ? $hutang->jatuh_tempo->format('d M Y') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="hs-info-lbl">Sales</div>
                                <div class="hs-info-val">{{ $hutang->penjualan->sales->nama ?? '-' }}</div>
                            </div>
                        </div>

                        @php
                            $pendingTotal = $hutang->pembayarans->where('status', 'pending')->sum('jumlah');
                            $effectiveSisa = max(0, (float) $hutang->sisa - (float) $pendingTotal);
                            $pct = (float) $hutang->total_hutang > 0 ? min(100, ((float) $hutang->dibayar / (float) $hutang->total_hutang) * 100) : 0;
                            $barColor = $pct >= 100 ? '#10b981' : ($pct > 0 ? '#f59e0b' : '#ef4444');
                        @endphp
                        <div class="hs-progress-wrap">
                            <div class="hs-progress-labels">
                                <span>Progress Pembayaran</span>
                                <span style="font-weight:700;">{{ number_format($pct, 1) }}%</span>
                            </div>
                            <div class="hs-progress-bar">
                                <div class="hs-progress-fill" id="progressFill" style="width:0%; background:{{ $barColor }};" data-pct="{{ (int) $pct }}"></div>
                            </div>
                            <div class="hs-progress-amounts">
                                <div class="hs-progress-amt" style="background:#f8fafc;">
                                    <div class="hs-progress-amt-lbl">Total Hutang</div>
                                    <div class="hs-progress-amt-val" style="color:#1e293b;">Rp {{ number_format((float) $hutang->total_hutang, 0, ',', '.') }}</div>
                                </div>
                                <div class="hs-progress-amt" style="background:#f0fdf4;">
                                    <div class="hs-progress-amt-lbl">Terbayar</div>
                                    <div class="hs-progress-amt-val" style="color:#059669;">Rp {{ number_format((float) $hutang->dibayar, 0, ',', '.') }}</div>
                                </div>
                                <div class="hs-progress-amt" style="background:#fef2f2;">
                                    <div class="hs-progress-amt-lbl">Sisa</div>
                                    <div class="hs-progress-amt-val" style="color:#dc2626;">Rp {{ number_format((float) $hutang->sisa, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            @if($pendingTotal > 0)
                            <div style="margin-top:0.75rem; padding:0.625rem 0.875rem; background:#fffbeb; border:1px solid #fde68a; border-radius:10px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <div style="font-size:0.625rem; font-weight:700; color:#92400e; text-transform:uppercase; letter-spacing:0.05em;">⏳ Pending Konfirmasi</div>
                                    <div style="font-size:0.75rem; color:#78350f; margin-top:0.25rem;">Menunggu persetujuan supervisor</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:0.9rem; font-weight:800; color:#d97706;">Rp {{ number_format($pendingTotal, 0, ',', '.') }}</div>
                                    <div style="font-size:0.625rem; color:#92400e;">Sisa efektif: Rp {{ number_format($effectiveSisa, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($hutang->keterangan)
                            <div class="hs-keterangan">📝 {{ $hutang->keterangan }}</div>
                        @endif
                    </div>
                </div>

                <div class="hs-card">
                    <div class="hs-card-hdr">
                        <div class="hs-card-hdr-ico" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); color:#059669;">💳</div>
                        <div class="hs-card-hdr-title">Riwayat Pembayaran</div>
                    </div>

                    @php
                        $pendingPayments = $hutang->pembayarans->where('status', 'pending');
                        $totalPending = $pendingPayments->sum('jumlah');
                    @endphp

                    @if($pendingPayments->count() > 0)
                    <div style="padding:1rem 1.5rem;">
                        <div class="hs-pending-alert">
                            <div class="hs-pending-alert-ico">⏳</div>
                            <div class="hs-pending-alert-txt">
                                <strong>{{ $pendingPayments->count() }} pembayaran</strong> menunggu konfirmasi supervisor.<br>
                                Total: <strong>Rp {{ number_format($totalPending, 0, ',', '.') }}</strong> — belum dihitung ke saldo hutang.
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($hutang->pembayarans->isEmpty())
                        <div class="hs-empty">Belum ada pembayaran yang tercatat.</div>
                    @else
                        <div class="hs-table-wrap">
                            <table class="hs-table">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jumlah</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        <th>Status</th>
                                        <th>Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hutang->pembayarans as $bayar)
                                    <tr>
                                        <td>{{ $bayar->tanggal_bayar ? $bayar->tanggal_bayar->format('d M Y') : '-' }}</td>
                                        <td class="amount">Rp {{ number_format((float) $bayar->jumlah, 0, ',', '.') }}</td>
                                        <td><span style="text-transform:capitalize;">{{ $bayar->cara_bayar }}</span></td>
                                        <td>
                                            @if($bayar->bukti_transfer)
                                                <a href="{{ asset('storage/' . $bayar->bukti_transfer) }}" target="_blank" style="display:inline-flex; align-items:center; gap:0.25rem; color:#3b82f6; font-size:0.75rem; font-weight:600; text-decoration:none;">
                                                    🖼️ Lihat
                                                </a>
                                            @else
                                                <span style="color:#94a3b8; font-size:0.75rem;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bayar->status === 'pending')
                                                <span class="hs-pay-badge pending">⏳ Pending</span>
                                            @elseif($bayar->status === 'confirmed')
                                                <span class="hs-pay-badge confirmed">✅ Confirmed</span>
                                            @else
                                                <span class="hs-pay-badge rejected">❌ Ditolak</span>
                                                @if($bayar->reject_reason)
                                                    <div style="font-size:0.625rem; color:#dc2626; margin-top:0.25rem;">{{ $bayar->reject_reason }}</div>
                                                @endif
                                            @endif

                                            {{-- Supervisor actions for pending payments --}}
                                            @if(!$isSalesRole && $bayar->status === 'pending')
                                            <div class="hs-pay-actions">
                                                <form action="{{ route('minyak.hutang.payment.confirm', [$hutang, $bayar]) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="hs-pay-btn hs-pay-btn-confirm" onclick="return confirm('Konfirmasi pembayaran Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}?')">
                                                        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                        Konfirmasi
                                                    </button>
                                                </form>
                                                <button type="button" class="hs-pay-btn hs-pay-btn-reject" onclick="toggleRejectForm('reject-{{ $bayar->id }}')">
                                                    <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    Tolak
                                                </button>
                                            </div>
                                            <form action="{{ route('minyak.hutang.payment.reject', [$hutang, $bayar]) }}" method="POST" class="hs-reject-form" id="reject-{{ $bayar->id }}">
                                                @csrf
                                                <textarea name="reject_reason" class="hs-reject-input" rows="2" placeholder="Alasan penolakan..." required></textarea>
                                                <div style="display:flex; gap:0.375rem; margin-top:0.375rem;">
                                                    <button type="submit" class="hs-pay-btn hs-pay-btn-reject" style="font-size:0.625rem;">Kirim Tolak</button>
                                                    <button type="button" class="hs-pay-btn" style="background:#f1f5f9; color:#64748b; border-color:#e2e8f0; font-size:0.625rem;" onclick="toggleRejectForm('reject-{{ $bayar->id }}')">Batal</button>
                                                </div>
                                            </form>
                                            @endif
                                        </td>
                                        <td style="color:#64748b; font-size:0.75rem;">
                                            {{ $bayar->creator->name ?? '-' }}
                                            @if($bayar->status === 'confirmed' && $bayar->confirmer)
                                                <div style="font-size:0.625rem; color:#059669;">✓ {{ $bayar->confirmer->name }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                @if($hutang->status !== 'lunas' && (float) $hutang->sisa > 0)
                    <div class="hs-card">
                        <div class="hs-card-hdr" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                            <div class="hs-card-hdr-ico" style="background:linear-gradient(135deg,#3b82f6,#2563eb); color:#fff;">💵</div>
                            <div class="hs-card-hdr-title">Catat Pembayaran</div>
                        </div>
                        <div class="hs-card-body">
                            <form action="{{ route('minyak.hutang.bayar', $hutang) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="hs-form-group">
                                    <label class="hs-form-label">Jumlah Bayar</label>
                                    <input type="number" name="jumlah" id="inp-jumlah-bayar" class="hs-form-input" min="1" max="{{ $effectiveSisa }}" placeholder="Maks: Rp {{ number_format($effectiveSisa, 0, ',', '.') }}" required>
                                </div>
                                <div class="hs-form-group">
                                    <label class="hs-form-label">Cara Bayar</label>
                                    <select name="cara_bayar" id="inp-cara-bayar" class="hs-form-input" required>
                                        <option value="tunai">💵 Tunai</option>
                                        <option value="transfer">🏦 Transfer</option>
                                    </select>
                                </div>

                                {{-- Bukti Transfer (muncul saat Transfer dipilih) --}}
                                <div class="hs-form-group" id="bukti-transfer-wrap" style="display:none;">
                                    <div class="hs-form-group" style="margin-bottom:.75rem;">
                                        <label class="hs-form-label">ID Transaksi <span style="color:#dc2626;">*Wajib</span></label>
                                        <input type="text" name="id_transaksi" id="inp-id-transaksi" class="hs-form-input"
                                               value="{{ old('id_transaksi') }}"
                                               placeholder="Contoh: TRX123456789"
                                               style="font-family:monospace; letter-spacing:.05em;">
                                        @error('id_transaksi')
                                            <div style="font-size:.72rem; color:#ef4444; margin-top:4px;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <label class="hs-form-label">Bukti Transfer <span style="color:#dc2626;">*Wajib foto</span></label>
                                    <input type="file" name="bukti_transfer" id="inp-bukti-transfer" accept="image/*" capture="environment" style="display:none;">
                                    <div id="bukti-preview-wrap" style="margin-top:0.5rem; display:none;">
                                        <img id="bukti-preview" src="" alt="Bukti Transfer" style="max-width:100%; max-height:200px; border-radius:0.75rem; border:0.125rem solid #10b981;">
                                    </div>
                                    <button type="button" id="btn-pilih-bukti" style="width:100%; padding:0.875rem; border:0.125rem dashed #94a3b8; border-radius:0.75rem; background:#f8fafc; color:#475569; font-weight:600; font-size:0.875rem; cursor:pointer; margin-top:0.5rem; transition:all 0.2s;">
                                        📷 Foto / Pilih Bukti Transfer
                                    </button>
                                </div>

                                <div class="hs-form-group">
                                    <label class="hs-form-label">Keterangan</label>
                                    <textarea name="keterangan" class="hs-form-input" rows="2" placeholder="Opsional..."></textarea>
                                </div>
                                <button type="submit" class="hs-btn hs-btn-primary">💾 Simpan Pembayaran</button>

                                @if($isSalesRole)
                                <div style="margin-top:0.875rem; padding:0.75rem; background:#fffbeb; border:1px solid #fde68a; border-radius:10px; font-size:0.6875rem; color:#92400e; line-height:1.6;">
                                    <strong>ℹ️ Aturan Pembayaran:</strong><br>
                                    • <strong>Tunai &lt; Rp 500.000</strong> → langsung dikonfirmasi<br>
                                    • <strong>Tunai ≥ Rp 500.000</strong> atau <strong>Transfer</strong> → menunggu persetujuan supervisor
                                </div>
                                @endif
                            </form>
                        </div>
                    </div>
                @else
                    <div class="hs-card">
                        <div class="hs-card-body hs-lunas-card">
                            <div class="hs-lunas-ico">✅</div>
                            <div class="hs-lunas-title">Hutang Lunas!</div>
                            <div class="hs-lunas-sub">Semua pembayaran telah selesai.</div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('minyak.hutang.index') }}" class="hs-btn" style="background:#f1f5f9; color:#475569; margin-top:0.75rem;">← Kembali</a>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            const el = document.getElementById('progressFill');
            if (!el) return;
            const pct = Math.max(0, Math.min(100, parseInt(el.dataset.pct || '0', 10)));
            requestAnimationFrame(() => { el.style.width = pct + '%'; });
        })();

        function toggleRejectForm(id) {
            var form = document.getElementById(id);
            if (form) {
                form.classList.toggle('active');
            }
        }

        // Show/hide bukti transfer based on cara_bayar
        (function() {
            var caraBayar = document.getElementById('inp-cara-bayar');
            var buktiWrap = document.getElementById('bukti-transfer-wrap');
            var buktiInput = document.getElementById('inp-bukti-transfer');
            var idTransaksiInput = document.getElementById('inp-id-transaksi');
            var btnPilih = document.getElementById('btn-pilih-bukti');
            var buktiPreview = document.getElementById('bukti-preview');
            var buktiPreviewWrap = document.getElementById('bukti-preview-wrap');

            function toggleBukti() {
                var isTransfer = caraBayar.value === 'transfer';
                buktiWrap.style.display = isTransfer ? 'block' : 'none';
                buktiInput.required = isTransfer;
                if (idTransaksiInput) idTransaksiInput.required = isTransfer;
                if (!isTransfer) {
                    buktiInput.value = '';
                    if (idTransaksiInput) idTransaksiInput.value = '';
                    buktiPreviewWrap.style.display = 'none';
                }
            }
            caraBayar.addEventListener('change', toggleBukti);

            btnPilih.addEventListener('click', function() {
                buktiInput.click();
            });

            buktiInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (!file) return;
                var reader = new FileReader();
                reader.onload = function(ev) {
                    buktiPreview.src = ev.target.result;
                    buktiPreviewWrap.style.display = 'block';
                    btnPilih.textContent = '✅ ' + file.name;
                    btnPilih.style.borderColor = '#10b981';
                    btnPilih.style.background = '#ecfdf5';
                    btnPilih.style.color = '#059669';
                };
                reader.readAsDataURL(file);
            });
        })();

        // Max validation for jumlah
        (function() {
            var inp = document.getElementById('inp-jumlah-bayar');
            if (inp) {
                inp.addEventListener('input', function() {
                    var v = parseInt(inp.value) || 0;
                    var max = {{ (int) $effectiveSisa }};
                    if (v > max) inp.value = max;
                    if (v < 0) inp.value = '';
                });
            }
        })();
    </script>
    @endpush
</x-app-layout>
