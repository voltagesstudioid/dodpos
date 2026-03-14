<x-app-layout>
    <x-slot name="header">Terima Barang — Purchase Order</x-slot>

    <div class="page-container" style="max-width:940px;">

        {{-- Context Banner: PO-linked --}}
        <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;display:flex;gap:.75rem;align-items:flex-start;">
            <span style="font-size:1.3rem;flex-shrink:0;">🛒</span>
            <div>
                <div style="font-weight:700;color:#1e40af;margin-bottom:.2rem;">Penerimaan Barang via Purchase Order</div>
                <div style="font-size:.8rem;color:#1d4ed8;line-height:1.6;">
                    Halaman ini mencatat penerimaan barang yang <strong>berhubungan langsung dengan Purchase Order</strong>.
                    Qty yang diinput akan mengurangi sisa pesanan di PO dan menambah stok gudang secara otomatis.<br>
                    <strong>Jika barang bukan dari PO</strong> (retur, koreksi, stok awal), gunakan
                    <a href="{{ route('gudang.penerimaan.create') }}" style="color:#1e40af;font-weight:700;text-decoration:underline;">Gudang → Penerimaan Barang</a>.
                </div>
            </div>
        </div>

        {{-- PO Summary Card --}}
        <div class="card" style="padding:1.25rem;margin-bottom:1rem;background:linear-gradient(135deg,#f8fafc,#eff6ff);border:1px solid #bfdbfe;">
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;">
                <div>
                    <div style="font-size:.65rem;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:.25rem;">No. PO</div>
                    <div style="font-weight:800;color:#4f46e5;font-family:monospace;">{{ $order->po_number }}</div>
                </div>
                <div>
                    <div style="font-size:.65rem;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:.25rem;">Supplier</div>
                    <div style="font-weight:700;">{{ $order->supplier->name }}</div>
                </div>
                <div>
                    <div style="font-size:.65rem;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:.25rem;">Status PO</div>
                    @php
                        $s = $order->status;
                        $badge = match($s) {
                            'ordered' => ['bg'=>'#dbeafe','color'=>'#1e40af','label'=>'Dipesan'],
                            'partial' => ['bg'=>'#fef3c7','color'=>'#92400e','label'=>'Sebagian Diterima'],
                            default   => ['bg'=>'#f1f5f9','color'=>'#475569','label'=>$s],
                        };
                    @endphp
                    <span style="background:{{ $badge['bg'] }};color:{{ $badge['color'] }};padding:.2rem .6rem;border-radius:999px;font-size:.75rem;font-weight:700;">{{ $badge['label'] }}</span>
                </div>
                <div>
                    <div style="font-size:.65rem;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:.25rem;">Total Nilai PO</div>
                    <div style="font-weight:700;color:#16a34a;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        @if(session('error')) <div class="alert alert-danger" style="margin-bottom:1rem;">❌ {{ session('error') }}</div> @endif
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:1rem;">
                <strong>Kesalahan:</strong>
                <ul style="margin:.5rem 0 0;padding-left:1.25rem;">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
            </div>
        @endif

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h1 style="font-size:1.2rem;font-weight:800;color:#0f172a;margin:0;display:flex;align-items:center;gap:.5rem;">
                <span style="background:#dbeafe;padding:.35rem .5rem;border-radius:8px;">📥</span>
                Proses Penerimaan Barang
            </h1>
            <a href="{{ route('pembelian.order.show', $order) }}" class="btn-secondary" style="font-size:.875rem;">← Detail PO</a>
        </div>

        <form action="{{ route('pembelian.order.process_receive', $order) }}" method="POST">
            @csrf

            {{-- Receipt Info --}}
            <div class="card" style="padding:1.5rem;margin-bottom:1rem;">
                <div style="font-weight:700;color:#334155;font-size:.875rem;margin-bottom:1rem;padding-bottom:.625rem;border-bottom:1px solid #f1f5f9;">
                    📋 Informasi Penerimaan
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Gudang Tujuan Penerimaan <span style="color:#ef4444;">*</span></label>
                        <select name="warehouse_id" class="form-input @error('warehouse_id') input-error @enderror" required>
                            <option value="">-- Pilih Gudang --</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" {{ old('warehouse_id')==$wh->id ? 'selected':'' }}>{{ $wh->name }}</option>
                            @endforeach
                        </select>
                        @error('warehouse_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Tanggal Penerimaan <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="receive_date" value="{{ old('receive_date', date('Y-m-d')) }}" class="form-input" required>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="card" style="margin-bottom:1.5rem;">
                <div style="padding:1rem 1.5rem;border-bottom:1px solid #f1f5f9;display:flex;justify-content:space-between;align-items:center;">
                    <h2 style="font-size:.95rem;font-weight:700;color:#334155;margin:0;">Detail Penerimaan per Item</h2>
                    <span style="font-size:.72rem;color:#64748b;">Qty boleh 0 jika item belum datang</span>
                </div>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th style="text-align:center;">Sisa Belum Terima</th>
                                <th style="text-align:center;background:#eff6ff;color:#1d4ed8;">Qty Terima <span style="color:#ef4444;">*</span></th>
                                <th style="text-align:center;">Kadaluwarsa (Opsional)</th>
                                <th style="text-align:center;">Batch (Opsional)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            @php $remaining = $item->qty_ordered - $item->qty_received; @endphp
                            <tr style="{{ $remaining == 0 ? 'opacity:0.45;' : '' }}">
                                <td>
                                    <input type="hidden" name="items[{{ $loop->index }}][item_id]" value="{{ $item->id }}">
                                    <div style="font-weight:700;color:#0f172a;">{{ $item->product->name }}</div>
                                    <div style="font-size:.65rem;color:#94a3b8;font-family:monospace;">{{ $item->product->sku }}</div>
                                </td>
                                <td style="text-align:center;">
                                    @if($remaining > 0)
                                        <span style="display:inline-block;padding:.2rem .6rem;border-radius:99px;background:#fef3c7;color:#92400e;font-weight:700;">{{ $remaining }}</span>
                                    @else
                                        <span style="color:#10b981;font-weight:700;font-size:.85rem;">✓ Selesai</span>
                                    @endif
                                </td>
                                <td style="text-align:center;background:#eff6ff;padding:.5rem 1rem;">
                                    @if($remaining > 0)
                                        <input type="number" name="items[{{ $loop->index }}][qty]"
                                               value="{{ old("items.{$loop->index}.qty", $remaining) }}"
                                               min="0" max="{{ $remaining }}" class="form-input"
                                               style="text-align:center;font-size:.9rem;font-weight:700;max-width:80px;margin:0 auto;">
                                    @else
                                        <input type="hidden" name="items[{{ $loop->index }}][qty]" value="0">
                                        <span style="color:#10b981;font-weight:700;">✓</span>
                                    @endif
                                </td>
                                <td style="text-align:center;padding:.5rem;">
                                    @if($remaining > 0)
                                        <input type="date" name="items[{{ $loop->index }}][expired_date]"
                                               value="{{ old("items.{$loop->index}.expired_date") }}"
                                               class="form-input" style="font-size:.8rem;max-width:130px;margin:0 auto;text-align:center;">
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                                <td style="text-align:center;padding:.5rem;">
                                    @if($remaining > 0)
                                        <input type="text" name="items[{{ $loop->index }}][batch_number]"
                                               value="{{ old("items.{$loop->index}.batch_number") }}"
                                               class="form-input" placeholder="No. Batch" style="font-size:.8rem;max-width:110px;margin:0 auto;text-align:center;">
                                    @else
                                        <span style="color:#cbd5e1;">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;gap:.75rem;">
                <a href="{{ route('pembelian.order.show', $order) }}" class="btn-secondary">Batal</a>
                <button type="submit" class="btn-primary" style="background:#3b82f6;border-color:#3b82f6;">
                    📥 Proses Penerimaan & Update Stok
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
