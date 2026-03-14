<x-app-layout>
    <x-slot name="header">Catat Hutang Supplier</x-slot>

    <div class="page-container" style="max-width:700px;">
        @if($errors->any())
            <div class="alert alert-danger">❌ {{ $errors->first() }}</div>
        @endif

        <div class="card" style="padding:2rem;">
            <div style="font-size:0.9rem;font-weight:700;color:#1e293b;margin-bottom:1.5rem;padding-bottom:0.75rem;border-bottom:1px solid #f1f5f9;">💳 Form Hutang Supplier</div>

            <form action="{{ route('pembelian.hutang.store') }}" method="POST">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Supplier <span style="color:#ef4444">*</span></label>
                        <select name="supplier_id" class="form-input @error('supplier_id') input-error @enderror" required>
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                                <option value="{{ $s->id }}" @selected(old('supplier_id') == $s->id)>{{ $s->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Referensi PO (opsional)</label>
                        <select name="purchase_order_id" class="form-input">
                            <option value="">-- Tanpa referensi PO --</option>
                            @foreach($purchaseOrders as $po)
                                <option value="{{ $po->id }}" @selected(old('purchase_order_id') == $po->id)>
                                    {{ $po->po_number }} — {{ $po->supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">No. Invoice / Faktur</label>
                    <input type="text" name="invoice_number" class="form-input" value="{{ old('invoice_number') }}" placeholder="Kosongkan untuk generate otomatis">
                    @error('invoice_number')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi <span style="color:#ef4444">*</span></label>
                        <input type="date" name="transaction_date" class="form-input @error('transaction_date') input-error @enderror"
                               value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        @error('transaction_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-input" value="{{ old('due_date') }}">
                        @error('due_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Total Hutang (Rp) <span style="color:#ef4444">*</span></label>
                    <input type="number" name="total_amount" class="form-input @error('total_amount') input-error @enderror"
                           value="{{ old('total_amount') }}" min="1" placeholder="0" required>
                    @error('total_amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="notes" class="form-input" rows="3" placeholder="Catatan pembelian...">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.5rem;">
                    <button type="submit" class="btn-primary">💾 Simpan Hutang</button>
                    <a href="{{ route('pembelian.hutang.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
