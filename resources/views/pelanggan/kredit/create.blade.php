<x-app-layout>
    <x-slot name="header">{{ $type === 'debt' ? 'Catat Hutang Pelanggan' : 'Catat Piutang / Kredit' }}</x-slot>

    <div class="page-container" style="max-width: 980px;">
        <div class="page-header">
            <div>
                <div class="page-header-title">{{ $type === 'debt' ? '💳 Catat Hutang Pelanggan' : '💰 Catat Piutang / Kredit' }}</div>
                <div class="page-header-subtitle">{{ $type === 'debt' ? 'Pelanggan beli kredit (menambah hutang)' : 'Kelebihan bayar / retur (piutang pelanggan)' }}</div>
            </div>
            <div class="page-header-actions">
                <a href="{{ route('pelanggan.kredit.index') }}" class="btn-secondary">← Kembali</a>
                <button type="submit" form="creditForm" class="btn-primary">💾 Simpan</button>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <div>❌ Periksa input Anda:</div>
                <div style="margin-top:0.35rem;">
                    <ul style="margin:0;padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Type selector --}}
        <div class="panel" style="margin-bottom: 1rem;">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Jenis Catatan</div>
                    <div class="panel-subtitle">Pilih jenis pencatatan yang sesuai</div>
                </div>
                <span class="badge {{ $type === 'debt' ? 'badge-warning' : 'badge-blue' }}">{{ $type === 'debt' ? 'Hutang' : 'Piutang' }}</span>
            </div>
            <div class="panel-body">
                <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'debt']) }}"
                       class="{{ $type==='debt' ? 'btn-primary' : 'btn-secondary' }}" style="flex:1;justify-content:center;text-align:center;min-width:260px;">
                        💳 Hutang Pelanggan
                        <span style="display:block;font-size:0.8rem;opacity:0.75;margin-top:0.2rem;">Pelanggan beli kredit</span>
                    </a>
                    <a href="{{ route('pelanggan.kredit.create', ['type'=>'credit']) }}"
                       class="{{ $type==='credit' ? 'btn-primary' : 'btn-secondary' }}" style="flex:1;justify-content:center;text-align:center;min-width:260px;">
                        💰 Piutang / Kredit
                        <span style="display:block;font-size:0.8rem;opacity:0.75;margin-top:0.2rem;">Kelebihan bayar / retur</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-header">
                <div>
                    <div class="panel-title">Detail Catatan</div>
                    <div class="panel-subtitle">Pelanggan, tanggal, jumlah, dan keterangan</div>
                </div>
                <span class="badge badge-gray">Form</span>
            </div>
            <div class="panel-body">
            <form id="creditForm" action="{{ route('pelanggan.kredit.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $type }}">

                <div class="form-group">
                    <label class="form-label">Pelanggan <span class="required">*</span></label>
                    <select name="customer_id" class="form-input @error('customer_id') input-error @enderror" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" @selected(old('customer_id') == $c->id)>
                                {{ $c->name }} @if($c->current_debt > 0)(Hutang: Rp {{ number_format($c->current_debt, 0, ',', '.') }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="form-hint">
                        Belum ada pelanggan? <a href="{{ route('pelanggan.create') }}">Tambah dulu</a>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Transaksi <span class="required">*</span></label>
                        <input type="date" name="transaction_date" class="form-input" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                        @error('transaction_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="due_date" class="form-input" value="{{ old('due_date') }}">
                        <div class="form-hint">Kosongkan jika tidak ada jatuh tempo.</div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Jumlah (Rp) <span class="required">*</span></label>
                    <input type="number" name="amount" class="form-input @error('amount') input-error @enderror"
                           value="{{ old('amount') }}" min="1" placeholder="0" required>
                    @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan <span class="required">*</span></label>
                    <input type="text" name="description" class="form-input" value="{{ old('description') }}"
                           placeholder="{{ $type === 'debt' ? 'Mis: Pembelian rokok kredit' : 'Mis: Kelebihan bayar transaksi #123' }}" required>
                    @error('description')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Catatan Tambahan</label>
                    <textarea name="notes" class="form-input" rows="2">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:0.75rem;flex-wrap:wrap;margin-top:1rem;">
                    <a href="{{ route('pelanggan.kredit.index') }}" class="btn-secondary">Batal</a>
                    <button type="submit" class="btn-primary">💾 Simpan</button>
                </div>
            </form>
            </div>
        </div>

        <div class="floating-bar">
            <span class="floating-bar-info">{{ $type === 'debt' ? 'Hutang akan menambah saldo hutang pelanggan.' : 'Piutang dicatat sebagai kelebihan bayar/retur.' }}</span>
            <div class="floating-bar-actions">
                <a href="{{ route('pelanggan.kredit.index') }}" class="btn-secondary">Batal</a>
                <button type="submit" form="creditForm" class="btn-primary">💾 Simpan</button>
            </div>
        </div>
    </div>
</x-app-layout>
