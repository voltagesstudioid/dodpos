<x-app-layout>
    <x-slot name="header">POS / Kasir</x-slot>

    <div class="page-container" style="max-width:980px;">
        <div class="card" style="padding:1.5rem;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div style="display:flex;gap:0.9rem;align-items:flex-start;">
                    <div style="width:46px;height:46px;border-radius:16px;display:flex;align-items:center;justify-content:center;background:#f1f5f9;border:1px solid #e2e8f0;font-size:1.4rem;">🔒</div>
                    <div>
                        <div style="font-size:1.25rem;font-weight:900;color:#0f172a;line-height:1.1;">Kasir Belum Dibuka</div>
                        <div style="font-size:0.875rem;color:#64748b;margin-top:0.35rem;max-width:640px;line-height:1.6;">
                            Anda belum bisa melakukan transaksi karena sesi kasir hari ini belum dibuka dengan modal awal.
                        </div>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-secondary">← Dashboard</a>
            </div>
        </div>

        @if(auth()->user()->role === 'supervisor')
            <div class="card" style="padding:1.5rem;margin-top:1rem;">
                <div style="font-size:0.9rem;font-weight:900;color:#0f172a;margin-bottom:0.25rem;">Buka Kasir Sekarang</div>
                <div style="font-size:0.85rem;color:#64748b;margin-bottom:1rem;line-height:1.6;">
                    Isi jumlah modal awal (uang kembalian) yang ada di laci kasir.
                </div>

                <form action="{{ route('kasir.open_session') }}" method="POST" style="display:flex;flex-direction:column;gap:0.75rem;">
                    @csrf
                    <div class="form-row">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Modal Awal (Rp)</label>
                            <input type="number" name="opening_amount" value="{{ old('opening_amount') }}" min="0" class="form-input @error('opening_amount') input-error @enderror" placeholder="0" required>
                            @error('opening_amount') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="payment_method" class="form-input @error('payment_method') input-error @enderror" required>
                                <option value="Tunai" @selected(old('payment_method')=='Tunai')>Tunai</option>
                                <option value="Transfer" @selected(old('payment_method')=='Transfer')>Transfer</option>
                            </select>
                            @error('payment_method') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="notes" rows="2" class="form-input" placeholder="Misal: Modal laci 1">{{ old('notes') }}</textarea>
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:0.5rem;flex-wrap:wrap;margin-top:0.25rem;">
                        <button type="submit" class="btn-primary">Simpan & Buka Kasir</button>
                    </div>
                </form>
            </div>
        @else
            <div class="card" style="padding:1rem;margin-top:1rem;border-left:3px solid #f59e0b;background:#fffbeb;">
                <div style="font-weight:900;color:#92400e;margin-bottom:0.25rem;">Akses dibatasi</div>
                <div style="font-size:0.85rem;color:#92400e;line-height:1.6;">
                    Silakan hubungi Supervisor untuk membuka sesi kasir dan memasukkan modal awal.
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
