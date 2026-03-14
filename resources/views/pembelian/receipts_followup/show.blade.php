<x-app-layout>
    <x-slot name="header">Review QC Receipt PO</x-slot>

    <div class="page-container" style="max-width: 1150px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:1rem;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0;">🔎 Review QC Receipt</h1>
                <div style="margin-top:0.35rem; color:#64748b; font-size:0.9rem; line-height:1.5;">
                    PO: <a href="{{ route('pembelian.order.show', $receipt->purchaseOrder) }}" style="font-weight:900; color:#1d4ed8; text-decoration:none;">{{ $receipt->purchaseOrder?->po_number ?? '-' }}</a>
                    · Supplier: <strong style="color:#0f172a;">{{ $receipt->purchaseOrder?->supplier?->name ?? '-' }}</strong>
                    · Gudang: <strong style="color:#0f172a;">{{ $receipt->warehouse?->name ?? '-' }}</strong>
                </div>
            </div>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('pembelian.receipts_followup.index') }}" class="btn-secondary">Kembali</a>
                <a href="{{ route('pembelian.order.show', $receipt->purchaseOrder) }}" class="btn-secondary">Detail PO</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1rem;">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div>
        @endif

        @php
            $statusBadge = $receipt->status === 'partial'
                ? ['#fee2e2', '#991b1b', 'PARTIAL']
                : ['#dcfce7', '#166534', 'COMPLETED'];
            $followup = $receipt->followup_status ?: 'open';
            $followupBadge = $followup === 'resolved'
                ? ['#dcfce7', '#166534', 'RESOLVED']
                : ['#fef3c7', '#92400e', 'OPEN'];
        @endphp

        <div class="card" style="padding:1.25rem; border:1px solid #e2e8f0; border-radius:12px; margin-bottom:1rem;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1rem;">
                <div>
                    <div style="font-size:0.75rem; color:#64748b; font-weight:900; letter-spacing:0.05em;">WAKTU</div>
                    <div style="font-weight:900; color:#0f172a; margin-top:0.25rem;">{{ $receipt->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#64748b; font-weight:900; letter-spacing:0.05em;">STATUS RECEIPT</div>
                    <div style="margin-top:0.35rem;">
                        <span style="display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:99px; font-weight:900; font-size:0.78rem; background:{{ $statusBadge[0] }}; color:{{ $statusBadge[1] }};">
                            {{ $statusBadge[2] }}
                        </span>
                    </div>
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#64748b; font-weight:900; letter-spacing:0.05em;">FOLLOW-UP</div>
                    <div style="margin-top:0.35rem;">
                        <span style="display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:99px; font-weight:900; font-size:0.78rem; background:{{ $followupBadge[0] }}; color:{{ $followupBadge[1] }};">
                            {{ $followupBadge[2] }}
                        </span>
                    </div>
                    @if($receipt->followup_status === 'resolved')
                        <div style="margin-top:0.35rem; font-size:0.8rem; color:#64748b;">
                            {{ $receipt->resolver?->name ?? '-' }} · {{ $receipt->resolved_at?->format('d/m/Y H:i') ?? '-' }}
                        </div>
                    @endif
                </div>
                <div>
                    <div style="font-size:0.75rem; color:#64748b; font-weight:900; letter-spacing:0.05em;">PENERIMA</div>
                    <div style="font-weight:900; color:#0f172a; margin-top:0.25rem;">{{ $receipt->receiver?->name ?? '-' }}</div>
                </div>
            </div>
            @if($receipt->notes)
                <div style="margin-top:0.9rem; color:#475569; font-size:0.9rem;">{{ $receipt->notes }}</div>
            @endif
            @if(is_array($receipt->photos) && count($receipt->photos) > 0)
                <div style="margin-top:0.9rem; display:flex; gap:0.5rem; flex-wrap:wrap;">
                    @foreach($receipt->photos as $p)
                        <a href="{{ asset('storage/'.$p) }}" target="_blank" style="display:inline-block; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                            <img src="{{ asset('storage/'.$p) }}" alt="Foto Bukti" style="width:110px; height:82px; object-fit:cover; display:block;">
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card" style="border-radius:12px; overflow:hidden; margin-bottom:1rem;">
            <div class="table-wrapper" style="overflow:auto;">
                <table class="data-table" style="width:100%; min-width: 980px;">
                    <thead>
                        <tr style="background:#fff;">
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Produk</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; text-align:center;">Sisa Sebelum</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; text-align:center;">Qty Terima (PO)</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; text-align:center;">Hasil</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">QC</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipt->items as $it)
                            @php
                                $badge = match($it->result) {
                                    'rejected' => ['#fee2e2', '#991b1b', 'REJECTED'],
                                    'partial' => ['#fef3c7', '#92400e', 'PARTIAL'],
                                    default => ['#dcfce7', '#166534', 'ACCEPTED'],
                                };
                                $qcBadges = [];
                                if (! $it->quality_ok) $qcBadges[] = ['Kualitas', '#fee2e2', '#991b1b'];
                                if (! $it->spec_ok) $qcBadges[] = ['Spesifikasi', '#fee2e2', '#991b1b'];
                                if (! $it->packaging_ok) $qcBadges[] = ['Kemasan', '#fee2e2', '#991b1b'];
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:1rem;">
                                    <div style="font-weight:900; color:#0f172a;">{{ $it->product?->name ?? '-' }}</div>
                                    <div style="font-size:0.75rem; color:#64748b; font-family:monospace;">{{ $it->product?->sku ?? '-' }}</div>
                                </td>
                                <td style="padding:1rem; text-align:center; font-weight:900;">{{ (int) $it->qty_remaining_before }}</td>
                                <td style="padding:1rem; text-align:center; font-weight:900;">{{ (int) $it->qty_received_po_unit }}</td>
                                <td style="padding:1rem; text-align:center;">
                                    <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:99px; font-weight:900; font-size:0.75rem; background:{{ $badge[0] }}; color:{{ $badge[1] }};">
                                        {{ $badge[2] }}
                                    </span>
                                </td>
                                <td style="padding:1rem; white-space:nowrap;">
                                    @if(count($qcBadges) === 0)
                                        <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:99px; font-weight:900; font-size:0.75rem; background:#dcfce7; color:#166534;">OK</span>
                                    @else
                                        @foreach($qcBadges as $qb)
                                            <span style="display:inline-flex; align-items:center; padding:0.2rem 0.55rem; border-radius:99px; font-weight:900; font-size:0.75rem; background:{{ $qb[1] }}; color:{{ $qb[2] }}; margin-right:0.25rem;">
                                                {{ $qb[0] }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td style="padding:1rem; color:#475569;">{{ $it->notes ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="padding:1.25rem; border:1px solid #e2e8f0; border-radius:12px;">
            <h3 style="font-size:1rem; font-weight:900; color:#0f172a; margin:0 0 0.75rem;">Tindak Lanjut Supervisor</h3>

            @if($receipt->followup_status === 'resolved')
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:1rem; color:#166534;">
                    <div style="font-weight:900; margin-bottom:0.35rem;">Follow-up sudah diselesaikan</div>
                    <div style="font-size:0.9rem; line-height:1.5;">
                        <strong>Aksi:</strong> {{ $receipt->followup_action ?? '-' }}<br>
                        <strong>Catatan:</strong> {{ $receipt->followup_notes ?? '-' }}
                    </div>
                </div>
            @else
                @if(! $receipt->reorder_purchase_order_id)
                    <form method="POST" action="{{ route('pembelian.receipts_followup.create_reorder_po', $receipt) }}" style="margin-bottom:0.75rem;" onsubmit="return confirm('Buat Draft PO Reorder untuk item shortage dari PO ini?');">
                        @csrf
                        <button type="submit" class="btn-secondary" style="width:100%; justify-content:center; display:flex;">
                            🛒 Buat Draft PO Reorder
                        </button>
                    </form>
                @else
                    <a href="{{ route('pembelian.order.edit', $receipt->reorder_purchase_order_id) }}" class="btn-secondary" style="width:100%; justify-content:center; display:flex; margin-bottom:0.75rem;">
                        🛒 Lihat Draft PO Reorder
                    </a>
                @endif

                @if(! $receipt->purchase_return_id)
                    <form method="POST" action="{{ route('pembelian.receipts_followup.create_retur', $receipt) }}" style="margin-bottom:0.75rem;" onsubmit="return confirm('Buat draft Retur Pembelian dari item QC bermasalah?');">
                        @csrf
                        <button type="submit" class="btn-secondary" style="width:100%; justify-content:center; display:flex;">
                            ↩️ Buat Draft Retur Pembelian
                        </button>
                    </form>
                @else
                    <a href="{{ route('pembelian.retur.show', $receipt->purchase_return_id) }}" class="btn-secondary" style="width:100%; justify-content:center; display:flex; margin-bottom:0.75rem;">
                        ↩️ Lihat Draft Retur
                    </a>
                @endif

                <form method="POST" action="{{ route('pembelian.receipts_followup.resolve', $receipt) }}" style="display:grid; grid-template-columns: 240px 1fr auto; gap:0.75rem; align-items:end;">
                    @csrf
                    <div>
                        <label class="form-label">Aksi</label>
                        <select name="followup_action" class="form-input" required>
                            <option value="">-- Pilih --</option>
                            <option value="return_to_supplier">Retur ke Supplier</option>
                            <option value="request_replacement">Minta Pengganti</option>
                            <option value="request_credit_note">Minta Nota Kredit</option>
                            <option value="accept_with_note">Terima dengan Catatan</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Catatan</label>
                        <input name="followup_notes" class="form-input" placeholder="Tulis keputusan & instruksi tindak lanjut..." required>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary" style="background:#22c55e; border-color:#22c55e;" onclick="return confirm('Simpan follow-up dan tandai resolved?');">✅ Resolve</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
