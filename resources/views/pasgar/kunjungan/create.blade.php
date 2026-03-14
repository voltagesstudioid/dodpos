<x-app-layout>
    <x-slot name="header">Tambah Laporan Kunjungan</x-slot>

    <div class="page-container" style="max-width:700px;">
        <div style="margin-bottom:1rem;">
            <a href="{{ route('pasgar.kunjungan.index') }}" class="btn-secondary btn-sm">← Kembali</a>
        </div>

        <div class="card">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid #e2e8f0;">
                <h2 style="font-size:1rem; font-weight:700; color:#1e293b;">📝 Laporan Hasil Kunjungan</h2>
                <p style="font-size:0.8rem; color:#64748b; margin-top:0.125rem;">Catat hasil kunjungan ke pelanggan</p>
            </div>

            <form method="POST" action="{{ route('pasgar.kunjungan.store') }}" style="padding:1.5rem;" id="visitForm">
                @csrf

                @if($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
                @endif

                {{-- Pre-filled from schedule if available --}}
                @if($schedule)
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:1rem; margin-bottom:1.25rem;">
                    <div style="font-size:0.8rem; font-weight:700; color:#1d4ed8; margin-bottom:0.5rem;">📅 Dari Jadwal Kunjungan</div>
                    <div style="font-size:0.875rem; color:#1e40af;">
                        <strong>{{ $schedule->member->user->name }}</strong> → <strong>{{ $schedule->customer->name }}</strong>
                        <span style="color:#64748b; margin-left:0.5rem;">{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('d M Y') }}</span>
                    </div>
                    <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                </div>
                @endif

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Anggota Pasgar <span style="color:#ef4444;">*</span></label>
                        <select name="pasgar_member_id" class="form-input {{ $errors->has('pasgar_member_id') ? 'input-error' : '' }}" required {{ $schedule ? 'disabled' : '' }}>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach($members as $m)
                                <option value="{{ $m->id }}"
                                    {{ old('pasgar_member_id', $schedule?->pasgar_member_id) == $m->id ? 'selected' : '' }}>
                                    {{ $m->user->name }} {{ $m->area ? '— '.$m->area : '' }}
                                </option>
                            @endforeach
                        </select>
                        @if($schedule)
                            <input type="hidden" name="pasgar_member_id" value="{{ $schedule->pasgar_member_id }}">
                        @endif
                        @error('pasgar_member_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Kunjungan <span style="color:#ef4444;">*</span></label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', today()->format('Y-m-d')) }}" class="form-input {{ $errors->has('visit_date') ? 'input-error' : '' }}" required>
                        @error('visit_date')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pelanggan <span style="color:#ef4444;">*</span></label>
                    <select name="customer_id" class="form-input {{ $errors->has('customer_id') ? 'input-error' : '' }}" required {{ $schedule ? 'disabled' : '' }}>
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}"
                                {{ old('customer_id', $schedule?->customer_id) == $c->id ? 'selected' : '' }}>
                                {{ $c->name }} {{ $c->phone ? '('.$c->phone.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @if($schedule)
                        <input type="hidden" name="customer_id" value="{{ $schedule->customer_id }}">
                    @endif
                    @error('customer_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Hasil Kunjungan <span style="color:#ef4444;">*</span></label>
                    <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:0.75rem;">
                        @php
                            $statusOptions = [
                                'order'     => ['icon' => '✅', 'label' => 'Ada Order',         'color' => '#10b981', 'bg' => '#f0fdf4', 'border' => '#bbf7d0'],
                                'no_order'  => ['icon' => '⚪', 'label' => 'Tidak Ada Order',   'color' => '#6366f1', 'bg' => '#eef2ff', 'border' => '#c7d2fe'],
                                'closed'    => ['icon' => '🔒', 'label' => 'Toko Tutup',        'color' => '#f59e0b', 'bg' => '#fffbeb', 'border' => '#fde68a'],
                                'not_found' => ['icon' => '❓', 'label' => 'Tidak Ditemukan',   'color' => '#ef4444', 'bg' => '#fef2f2', 'border' => '#fecaca'],
                            ];
                        @endphp
                        @foreach($statusOptions as $val => $opt)
                        <label style="display:flex; align-items:center; gap:0.75rem; padding:0.875rem; border:2px solid {{ old('status') === $val ? $opt['border'] : '#e2e8f0' }}; border-radius:10px; cursor:pointer; background:{{ old('status') === $val ? $opt['bg'] : 'white' }}; transition:all 0.15s;" onclick="selectStatus('{{ $val }}', this)">
                            <input type="radio" name="status" value="{{ $val }}" {{ old('status') === $val ? 'checked' : '' }} style="display:none;">
                            <span style="font-size:1.25rem;">{{ $opt['icon'] }}</span>
                            <span style="font-weight:600; color:{{ $opt['color'] }}; font-size:0.875rem;">{{ $opt['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('status')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row" id="amountFields">
                    <div class="form-group">
                        <label class="form-label">Nilai Order (Rp)</label>
                        <input type="number" name="order_amount" value="{{ old('order_amount', 0) }}" min="0" step="1000" class="form-input {{ $errors->has('order_amount') ? 'input-error' : '' }}">
                        @error('order_amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Penagihan Diterima (Rp)</label>
                        <input type="number" name="collection_amount" value="{{ old('collection_amount', 0) }}" min="0" step="1000" class="form-input {{ $errors->has('collection_amount') ? 'input-error' : '' }}">
                        @error('collection_amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan / Keterangan</label>
                    <textarea name="notes" rows="3" placeholder="Kondisi toko, alasan tidak order, janji kunjungan berikutnya, dll..." class="form-input {{ $errors->has('notes') ? 'input-error' : '' }}" style="resize:vertical;">{{ old('notes') }}</textarea>
                    @error('notes')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div style="display:flex; gap:0.75rem; padding-top:0.5rem; border-top:1px solid #f1f5f9; margin-top:0.5rem;">
                    <button type="submit" class="btn-primary">📝 Simpan Laporan</button>
                    <a href="{{ route('pasgar.kunjungan.index') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const statusColors = {
            'order':     { border: '#bbf7d0', bg: '#f0fdf4' },
            'no_order':  { border: '#c7d2fe', bg: '#eef2ff' },
            'closed':    { border: '#fde68a', bg: '#fffbeb' },
            'not_found': { border: '#fecaca', bg: '#fef2f2' },
        };

        function selectStatus(val, labelEl) {
            // Reset all
            document.querySelectorAll('input[name="status"]').forEach(r => {
                const lbl = r.closest('label');
                lbl.style.borderColor = '#e2e8f0';
                lbl.style.background = 'white';
            });
            // Set selected
            labelEl.style.borderColor = statusColors[val].border;
            labelEl.style.background = statusColors[val].bg;
            labelEl.querySelector('input[type="radio"]').checked = true;

            // Show/hide amount fields
            const amountFields = document.getElementById('amountFields');
            amountFields.style.display = (val === 'order') ? 'grid' : 'none';
        }

        // Init on load
        const checked = document.querySelector('input[name="status"]:checked');
        if (checked) {
            selectStatus(checked.value, checked.closest('label'));
        } else {
            document.getElementById('amountFields').style.display = 'none';
        }
    </script>
</x-app-layout>
