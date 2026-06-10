<x-app-layout>
    <x-slot name="header">Review QC Receipt PO</x-slot>

    <div class="page-container" style="max-width: 1200px;">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); padding: 1.5rem; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px rgba(0,0,0,0.02);">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 54px; height: 54px; border-radius: 14px; background: #ffffff; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05); font-size: 1.75rem;">
                    🔎
                </div>
                <div>
                    <h1 style="font-size:1.6rem; font-weight:900; color:#0f172a; margin:0; letter-spacing: -0.02em;">Review QC Receipt</h1>
                    <div style="margin-top:0.35rem; color:#64748b; font-size:0.95rem; font-weight: 500;">
                        PO: <a href="{{ route('pembelian.order.show', $receipt->purchaseOrder) }}" style="font-weight:900; color:#4f46e5; text-decoration:none;">{{ $receipt->purchaseOrder?->po_number ?? '-' }}</a>
                        <span style="margin: 0 8px; color: #cbd5e1;">|</span> Supplier: <strong style="color:#0f172a;">{{ $receipt->purchaseOrder?->supplier?->name ?? '-' }}</strong>
                        <span style="margin: 0 8px; color: #cbd5e1;">|</span> Gudang: <strong style="color:#0f172a;">{{ $receipt->warehouse?->name ?? '-' }}</strong>
                    </div>
                </div>
            </div>
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                <a href="{{ route('pembelian.receipts_followup.index') }}" class="btn-secondary" style="border-radius: 10px; padding: 0.6rem 1.25rem;">⬅️ Kembali</a>
                @if($receipt->purchaseOrder)
                    <a href="{{ route('pembelian.order.show', $receipt->purchaseOrder) }}" class="btn-secondary" style="border-radius: 10px; padding: 0.6rem 1.25rem;">📄 Detail PO</a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:1.5rem; border-radius: 12px; font-weight: 600;">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger" style="margin-bottom:1.5rem; border-radius: 12px; font-weight: 600;">❌ {{ session('error') }}</div>
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

        <div class="card" style="padding:1.5rem; border:1px solid #e2e8f0; border-radius:16px; margin-bottom:1.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.5rem;">
                <div style="background: #f8fafc; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:800; text-transform: uppercase; letter-spacing:0.05em;">Waktu Terima</div>
                    <div style="font-weight:900; color:#0f172a; font-size: 1.1rem; margin-top:0.35rem;">{{ $receipt->created_at->format('d M Y') }}</div>
                    <div style="color: #64748b; font-size: 0.85rem; font-weight: 600;">{{ $receipt->created_at->format('H:i') }} WIB</div>
                </div>
                <div style="background: #f8fafc; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:800; text-transform: uppercase; letter-spacing:0.05em;">Status Penerimaan</div>
                    <div style="margin-top:0.5rem;">
                        <span style="display:inline-flex; align-items:center; padding:0.35rem 0.85rem; border-radius:10px; font-weight:800; font-size:0.8rem; background:{{ $statusBadge[0] }}; color:{{ $statusBadge[1] }}; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            {{ $statusBadge[2] }}
                        </span>
                    </div>
                </div>
                <div style="background: #f8fafc; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:800; text-transform: uppercase; letter-spacing:0.05em;">Status Follow-up</div>
                    <div style="margin-top:0.5rem;">
                        <span style="display:inline-flex; align-items:center; padding:0.35rem 0.85rem; border-radius:10px; font-weight:800; font-size:0.8rem; background:{{ $followupBadge[0] }}; color:{{ $followupBadge[1] }}; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            {{ $followupBadge[2] }}
                        </span>
                    </div>
                    @if($receipt->followup_status === 'resolved')
                        <div style="margin-top:0.5rem; font-size:0.8rem; font-weight: 600; color:#64748b;">
                            Oleh: <span style="color: #0f172a;">{{ $receipt->resolver?->name ?? '-' }}</span><br>
                            Pada: {{ $receipt->resolved_at?->format('d/m/Y H:i') ?? '-' }}
                        </div>
                    @endif
                </div>
                <div style="background: #f8fafc; padding: 1rem 1.25rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                    <div style="font-size:0.75rem; color:#64748b; font-weight:800; text-transform: uppercase; letter-spacing:0.05em;">Penerima & Pemeriksa</div>
                    <div style="font-weight:900; color:#0f172a; font-size: 1.1rem; margin-top:0.35rem;">{{ $receipt->receiver?->name ?? '-' }}</div>
                </div>
            </div>
            @if($receipt->notes)
                <div style="margin-top:1.5rem; padding: 1.25rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; color:#92400e; font-size:0.95rem; font-weight: 500;">
                    <strong style="display: block; margin-bottom: 0.25rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">📝 Catatan Penerima:</strong>
                    {{ $receipt->notes }}
                </div>
            @endif
            @if(is_array($receipt->photos) && count($receipt->photos) > 0)
                <div style="margin-top:1.5rem;">
                    <div style="font-size:0.85rem; font-weight:800; margin-bottom: 0.75rem; color: #334155;">📷 Foto Bukti Kedatangan / Reject:</div>
                    <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
                        @foreach($receipt->photos as $p)
                            <a href="{{ asset('storage/'.$p) }}" target="_blank" style="display:inline-block; border:2px solid #e2e8f0; border-radius:12px; overflow:hidden; transition: all 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" onmouseover="this.style.borderColor='#cbd5e1'; this.style.transform='translateY(-2px)';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.transform='none';">
                                <img src="{{ asset('storage/'.$p) }}" alt="Foto Bukti" style="width:140px; height:105px; object-fit:cover; display:block;">
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="card" style="border-radius:16px; overflow:hidden; margin-bottom:1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="padding: 1.25rem 1.5rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-weight: 900; font-size: 1.1rem; color: #0f172a;">📦 Rincian Item (QC)</h3>
            </div>
            <div class="table-wrapper" style="overflow:auto;">
                <table class="data-table" style="width:100%; min-width: 980px; border-collapse: collapse;">
                    <thead>
                        <tr style="background:#ffffff;">
                            <th style="padding:1rem 1.5rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; font-weight: 800; color:#64748b; letter-spacing:0.05em; text-transform: uppercase; text-align: left;">Produk</th>
                            <th style="padding:1rem 1.5rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; font-weight: 800; color:#64748b; letter-spacing:0.05em; text-transform: uppercase; text-align:center;">Sisa Sblm</th>
                            <th style="padding:1rem 1.5rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; font-weight: 800; color:#64748b; letter-spacing:0.05em; text-transform: uppercase; text-align:center;">Diterima</th>
                            <th style="padding:1rem 1.5rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; font-weight: 800; color:#64748b; letter-spacing:0.05em; text-transform: uppercase; text-align:center;">Status</th>
                            <th style="padding:1rem 1.5rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; font-weight: 800; color:#64748b; letter-spacing:0.05em; text-transform: uppercase; text-align: left;">Parameter QC</th>
                            <th style="padding:1rem 1.5rem; border-bottom:2px solid #e2e8f0; font-size:0.75rem; font-weight: 800; color:#64748b; letter-spacing:0.05em; text-transform: uppercase; text-align: left;">Catatan / Alasan Reject</th>
                        </tr>
                    </thead>
                    <tbody style="background: #ffffff;">
                        @foreach($receipt->items as $it)
                            @php
                                $badge = match($it->result) {
                                    'rejected' => ['#fee2e2', '#991b1b', 'REJECTED'],
                                    'partial' => ['#fef3c7', '#92400e', 'PARTIAL'],
                                    default => ['#dcfce7', '#166534', 'ACCEPTED'],
                                };
                                $qcBadges = [];
                                if (! $it->quality_ok) $qcBadges[] = ['❌ Kualitas', '#fee2e2', '#991b1b'];
                                if (! $it->spec_ok) $qcBadges[] = ['❌ Spesifikasi', '#fee2e2', '#991b1b'];
                                if (! $it->packaging_ok) $qcBadges[] = ['❌ Kemasan', '#fee2e2', '#991b1b'];
                            @endphp
                            <tr style="border-bottom:1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='transparent';">
                                <td style="padding:1.25rem 1.5rem;">
                                    <div style="font-weight:800; color:#0f172a; font-size: 0.95rem;">{{ $it->product?->name ?? '-' }}</div>
                                    <div style="font-size:0.8rem; color:#64748b; font-family: ui-monospace, monospace; margin-top: 0.25rem; font-weight: 600;">{{ $it->product?->sku ?? '-' }}</div>
                                </td>
                                <td style="padding:1.25rem 1.5rem; text-align:center; font-weight:900; font-size: 1.05rem; color: #475569;">{{ (int) $it->qty_remaining_before }}</td>
                                <td style="padding:1.25rem 1.5rem; text-align:center; font-weight:900; font-size: 1.05rem; color: #0f172a;">{{ (int) $it->qty_received_po_unit }}</td>
                                <td style="padding:1.25rem 1.5rem; text-align:center;">
                                    <span style="display:inline-flex; align-items:center; padding:0.35rem 0.75rem; border-radius:10px; font-weight:800; font-size:0.75rem; background:{{ $badge[0] }}; color:{{ $badge[1] }}; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                        {{ $badge[2] }}
                                    </span>
                                </td>
                                <td style="padding:1.25rem 1.5rem; white-space:nowrap;">
                                    @if(count($qcBadges) === 0)
                                        <span style="display:inline-flex; align-items:center; padding:0.35rem 0.75rem; border-radius:10px; font-weight:800; font-size:0.75rem; background:#dcfce7; color:#166534; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">✅ Lulus QC</span>
                                    @else
                                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                            @foreach($qcBadges as $qb)
                                                <span style="display:inline-flex; align-items:center; padding:0.35rem 0.75rem; border-radius:10px; font-weight:800; font-size:0.75rem; background:{{ $qb[1] }}; color:{{ $qb[2] }}; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                                    {{ $qb[0] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </td>
                                <td style="padding:1.25rem 1.5rem; color:#475569; font-weight: 500; font-size: 0.9rem;">
                                    {{ $it->notes ?: '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="padding:1.5rem; border:1px solid #e2e8f0; border-radius:16px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; border-bottom: 2px dashed #e2e8f0; padding-bottom: 1rem;">
                <h3 style="font-size:1.2rem; font-weight:900; color:#0f172a; margin:0;">🛡️ Tindak Lanjut Supervisor</h3>
            </div>

            @if($receipt->followup_status === 'resolved')
                <div style="background:linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border:1px solid #a7f3d0; border-radius:12px; padding:1.5rem; color:#065f46; display: flex; gap: 1rem; align-items: flex-start; box-shadow: 0 4px 10px rgba(16,185,129,0.05);">
                    <div style="font-size: 2rem; line-height: 1;">✅</div>
                    <div>
                        <div style="font-weight:900; margin-bottom:0.5rem; font-size: 1.1rem;">Follow-up Telah Selesai (Resolved)</div>
                        <div style="font-size:0.95rem; line-height:1.6; font-weight: 500;">
                            <strong>Aksi yang Diambil:</strong> <span style="text-transform: capitalize; border-bottom: 1px dashed #065f46;">{{ str_replace('_', ' ', $receipt->followup_action ?? '-') }}</span><br>
                            <strong>Catatan Keputusan:</strong> {{ $receipt->followup_notes ?? '-' }}
                        </div>
                    </div>
                </div>
            @else
                <div style="display: grid; grid-template-columns: 1fr; gap: 1.5rem;">
                    
                    {{-- TOMBOL TINDAKAN OTOMATIS --}}
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; background: #f8fafc; padding: 1.25rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                        @if(! $receipt->reorder_purchase_order_id)
                            <form method="POST" action="{{ route('pembelian.receipts_followup.create_reorder_po', $receipt) }}" onsubmit="return confirm('Sistem akan membuat Draft PO baru untuk menutupi item shortage dari PO ini secara otomatis. Lanjutkan?');">
                                @csrf
                                <button type="submit" class="btn-primary" style="width:100%; justify-content:center; display:flex; padding: 0.85rem; border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); font-size: 0.95rem; box-shadow: 0 4px 10px rgba(59,130,246,0.25);">
                                    🛒 Buat Draft PO Reorder
                                </button>
                                <div style="font-size: 0.75rem; color: #64748b; text-align: center; margin-top: 0.5rem; font-weight: 600;">(Otomatis menjadi Resolved)</div>
                            </form>
                        @else
                            <a href="{{ route('pembelian.order.edit', $receipt->reorder_purchase_order_id) }}" class="btn-secondary" style="width:100%; justify-content:center; display:flex; padding: 0.85rem; border-radius: 10px; font-size: 0.95rem; border: 2px solid #cbd5e1; font-weight: 800; color: #0f172a;">
                                📄 Lihat Draft PO Reorder
                            </a>
                        @endif

                        @if(! $receipt->purchase_return_id)
                            <form method="POST" action="{{ route('pembelian.receipts_followup.create_retur', $receipt) }}" onsubmit="return confirm('Sistem akan membuat Dokumen Retur Pembelian dari item yang gagal QC / Reject secara otomatis. Lanjutkan?');">
                                @csrf
                                <button type="submit" class="btn-primary" style="width:100%; justify-content:center; display:flex; padding: 0.85rem; border-radius: 10px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-size: 0.95rem; box-shadow: 0 4px 10px rgba(245,158,11,0.25); border-color: transparent;">
                                    ↩️ Buat Draft Retur (Ke Supplier)
                                </button>
                                <div style="font-size: 0.75rem; color: #64748b; text-align: center; margin-top: 0.5rem; font-weight: 600;">(Otomatis menjadi Resolved)</div>
                            </form>
                        @else
                            <a href="{{ route('pembelian.retur.show', $receipt->purchase_return_id) }}" class="btn-secondary" style="width:100%; justify-content:center; display:flex; padding: 0.85rem; border-radius: 10px; font-size: 0.95rem; border: 2px solid #cbd5e1; font-weight: 800; color: #0f172a;">
                                📄 Lihat Dokumen Retur
                            </a>
                        @endif
                    </div>

                    <div style="text-align: center; position: relative;">
                        <span style="background: #ffffff; padding: 0 1rem; color: #94a3b8; font-weight: 800; font-size: 0.85rem; position: relative; z-index: 2;">ATAU TUTUP MANUAL</span>
                        <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: #e2e8f0; z-index: 1;"></div>
                    </div>

                    {{-- RESOLVE MANUAL --}}
                    <form method="POST" action="{{ route('pembelian.receipts_followup.resolve', $receipt) }}" style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid #f1f5f9;">
                        @csrf
                        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:1.25rem; align-items:start;">
                            <div>
                                <label class="form-label" style="font-weight: 800; color: #0f172a; font-size: 0.85rem;">Pilih Aksi / Keputusan</label>
                                <select name="followup_action" class="form-input" required style="padding: 0.85rem; border-radius: 10px; border: 2px solid #e2e8f0; font-weight: 600;">
                                    <option value="">-- Pilih --</option>
                                    <option value="return_to_supplier">Retur ke Supplier (Manual)</option>
                                    <option value="request_replacement">Minta Pengganti (Manual)</option>
                                    <option value="request_credit_note">Minta Nota Kredit</option>
                                    <option value="accept_with_note">Terima Barang (Toleransi Reject)</option>
                                    <option value="other">Tindakan Lainnya</option>
                                </select>
                            </div>
                            <div style="grid-column: span 2;">
                                <label class="form-label" style="font-weight: 800; color: #0f172a; font-size: 0.85rem;">Catatan Resolusi</label>
                                <input type="text" name="followup_notes" class="form-input" placeholder="Tulis instruksi lebih lanjut atau justifikasi keputusan..." required style="padding: 0.85rem; border-radius: 10px; border: 2px solid #e2e8f0; font-weight: 500;">
                            </div>
                        </div>
                        <div style="margin-top: 1.5rem; text-align: right;">
                            <button type="submit" class="btn-primary" style="background:linear-gradient(135deg, #10b981 0%, #059669 100%); border-color:transparent; padding: 0.85rem 2rem; border-radius: 10px; font-weight: 800; font-size: 0.95rem; box-shadow: 0 4px 10px rgba(16,185,129,0.25);" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan follow-up ini secara manual?');">
                                ✅ Tandai Selesai (Resolve)
                            </button>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
