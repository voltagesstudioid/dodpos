<x-app-layout>
    <x-slot name="header">QC Follow-up PO</x-slot>

    <div class="page-container" style="max-width: 1150px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem; flex-wrap:wrap; margin-bottom:1rem;">
            <div>
                <h1 style="font-size:1.5rem; font-weight:800; color:#0f172a; margin:0;">⚠️ QC Follow-up (Purchase Order)</h1>
                <p style="margin:0.35rem 0 0; color:#64748b; font-size:0.9rem; line-height:1.5;">
                    Daftar penerimaan PO yang memiliki selisih / reject / QC tidak OK dan perlu tindak lanjut.
                </p>
            </div>
            <a href="{{ route('pembelian.order') }}" class="btn-secondary">🛒 Ke Daftar PO</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1rem;">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div>
        @endif

        <div class="panel" style="margin-bottom:1rem;">
            <form method="GET" action="{{ route('pembelian.receipts_followup.index') }}" style="display:grid; grid-template-columns: 1fr 180px auto; gap:0.75rem; align-items:end;">
                <div>
                    <label class="form-label">Cari</label>
                    <input name="q" value="{{ request('q') }}" class="form-input" placeholder="Cari PO / supplier / gudang / penerima...">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                </div>
                <div style="display:flex; gap:0.5rem;">
                    <button type="submit" class="btn-primary">Filter</button>
                    @if(request()->filled('q') || request()->filled('status'))
                        <a href="{{ route('pembelian.receipts_followup.index') }}" class="btn-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card" style="border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05);">
            <div class="table-wrapper" style="overflow:auto;">
                <table class="data-table" style="width:100%; min-width: 980px;">
                    <thead>
                        <tr style="background:#fff;">
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Waktu</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">PO</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Supplier</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Gudang</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Status</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Follow-up</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Retur</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em;">Reorder</th>
                            <th style="padding:1rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; color:#64748b; letter-spacing:0.05em; text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receipts as $r)
                            @php
                                $st = $r->status === 'partial' ? ['#fee2e2', '#991b1b', 'PARTIAL'] : ['#dcfce7', '#166534', 'COMPLETED'];
                                $fs = $r->followup_status ?: 'open';
                                $fsBadge = $fs === 'resolved' ? ['#dcfce7', '#166534', 'RESOLVED'] : ['#fef3c7', '#92400e', 'OPEN'];
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:1rem; white-space:nowrap;">
                                    <div style="font-weight:800; color:#0f172a;">{{ $r->created_at->format('d/m/Y') }}</div>
                                    <div style="font-size:0.75rem; color:#64748b;">{{ $r->created_at->format('H:i') }}</div>
                                </td>
                                <td style="padding:1rem; white-space:nowrap;">
                                    <a href="{{ route('pembelian.order.show', $r->purchaseOrder) }}" style="font-weight:900; color:#1d4ed8; text-decoration:none;">
                                        {{ $r->purchaseOrder?->po_number ?? '-' }}
                                    </a>
                                </td>
                                <td style="padding:1rem;">
                                    <div style="font-weight:800; color:#0f172a;">{{ $r->purchaseOrder?->supplier?->name ?? '-' }}</div>
                                </td>
                                <td style="padding:1rem;">
                                    <div style="font-weight:800; color:#0f172a;">{{ $r->warehouse?->name ?? '-' }}</div>
                                    <div style="font-size:0.75rem; color:#64748b;">Penerima: {{ $r->receiver?->name ?? '-' }}</div>
                                </td>
                                <td style="padding:1rem;">
                                    <span style="display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:99px; font-weight:900; font-size:0.78rem; background:{{ $st[0] }}; color:{{ $st[1] }};">
                                        {{ $st[2] }}
                                    </span>
                                </td>
                                <td style="padding:1rem;">
                                    <span style="display:inline-flex; align-items:center; padding:0.25rem 0.65rem; border-radius:99px; font-weight:900; font-size:0.78rem; background:{{ $fsBadge[0] }}; color:{{ $fsBadge[1] }};">
                                        {{ $fsBadge[2] }}
                                    </span>
                                    @if($r->followup_status === 'resolved' && $r->resolved_at)
                                        <div style="font-size:0.75rem; color:#64748b; margin-top:0.35rem;">{{ $r->resolved_at->format('d/m/Y H:i') }}</div>
                                    @endif
                                </td>
                                <td style="padding:1rem; white-space:nowrap;">
                                    @if($r->purchase_return_id)
                                        <a href="{{ route('pembelian.retur.show', $r->purchase_return_id) }}" style="font-weight:900; color:#1d4ed8; text-decoration:none;">
                                            Lihat Draft
                                        </a>
                                    @else
                                        <span style="color:#64748b;">-</span>
                                    @endif
                                </td>
                                <td style="padding:1rem; white-space:nowrap;">
                                    @if($r->reorder_purchase_order_id)
                                        <a href="{{ route('pembelian.order.edit', $r->reorder_purchase_order_id) }}" style="font-weight:900; color:#1d4ed8; text-decoration:none;">
                                            Lihat Draft
                                        </a>
                                    @else
                                        <span style="color:#64748b;">-</span>
                                    @endif
                                </td>
                                <td style="padding:1rem; text-align:right; white-space:nowrap;">
                                    <a href="{{ route('pembelian.receipts_followup.show', $r) }}" class="btn-primary" style="font-size:0.85rem; background:#0ea5e9; border-color:#0ea5e9;">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="padding:2.5rem; text-align:center; color:#64748b;">
                                    Tidak ada receipt yang membutuhkan follow-up.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($receipts->hasPages())
                <div class="panel-footer">{{ $receipts->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
