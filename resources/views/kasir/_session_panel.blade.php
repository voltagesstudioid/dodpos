{{-- Session Panel Partial — rendered per tab --}}
{{-- Expects: $s, $st, $type, $label, $closeRoute, $accent --}}

{{-- 1. HERO --}}
<div class="sp-hero">
    <div class="sp-hero-glow"></div>
    <div class="sp-hero-content">
        <div>
            <span class="sp-hero-label">Estimasi Uang Fisik Laci — {{ $label }}</span>
            <div class="sp-hero-amount">
                <small>Rp</small>{{ number_format($st['expectedCash'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="sp-hero-chips">
                <span class="sp-chip">Modal</span><span class="sp-chip-op">+</span>
                <span class="sp-chip">Tunai</span><span class="sp-chip-op">+</span>
                <span class="sp-chip">DP</span><span class="sp-chip-op">+</span>
                <span class="sp-chip">Cash In</span><span class="sp-chip-op">−</span>
                <span class="sp-chip">Cash Out</span>
            </div>
        </div>
        <div class="sp-hero-right">
            <div class="sp-status"><span class="sp-status-dot"></span> OPEN</div>
            <div class="sp-meta">
                <span>Oleh: <strong>{{ $s->user?->name ?? '-' }}</strong></span><br>
                <span>Buka: {{ optional($s->opened_at ?? $s->created_at)->format('H:i, d M Y') }}</span>
            </div>
        </div>
    </div>
</div>

{{-- 2. BREAKDOWN --}}
<h3 class="sp-section-title">Komponen Perhitungan Kas</h3>
<div class="sp-bd-grid">
    <div class="sp-bd">
        <div class="sp-bd-icon i-modal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg></div>
        <div><span class="sp-bd-lbl">Modal Awal</span><br><span class="sp-bd-val">Rp {{ number_format($s->opening_amount ?? 0, 0, ',', '.') }}</span></div>
        <span class="sp-bd-sign sp-bd-plus">+</span>
    </div>
    <div class="sp-bd">
        <div class="sp-bd-icon i-cash"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div>
        <div><span class="sp-bd-lbl">Penjualan Tunai</span><br><span class="sp-bd-val">Rp {{ number_format($st['cashRevenue'] ?? 0, 0, ',', '.') }}</span><br><span class="sp-bd-sub">{{ $st['cashTransactions'] ?? 0 }} transaksi</span></div>
        <span class="sp-bd-sign sp-bd-plus">+</span>
    </div>
    <div class="sp-bd">
        <div class="sp-bd-icon i-dp"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg></div>
        <div><span class="sp-bd-lbl">DP Kredit</span><br><span class="sp-bd-val">Rp {{ number_format($st['creditDp'] ?? 0, 0, ',', '.') }}</span></div>
        <span class="sp-bd-sign sp-bd-plus">+</span>
    </div>
    <div class="sp-bd">
        <div class="sp-bd-icon i-in"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
        <div><span class="sp-bd-lbl">Cash In</span><br><span class="sp-bd-val">Rp {{ number_format($st['cashIn'] ?? 0, 0, ',', '.') }}</span></div>
        <span class="sp-bd-sign sp-bd-plus">+</span>
    </div>
    <div class="sp-bd">
        <div class="sp-bd-icon i-out"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"/><polyline points="17 18 23 18 23 12"/></svg></div>
        <div><span class="sp-bd-lbl">Cash Out</span><br><span class="sp-bd-val">Rp {{ number_format($st['cashOut'] ?? 0, 0, ',', '.') }}</span></div>
        <span class="sp-bd-sign sp-bd-minus">−</span>
    </div>
</div>

{{-- 3. STATS --}}
<div class="sp-stats">
    <div class="sp-stat" style="border-left: 3px solid #4f46e5;">
        <div class="sp-stat-top"><span class="sp-stat-lbl">Total Omzet</span><span class="sp-stat-badge">Semua Metode</span></div>
        <div class="sp-stat-amt">Rp {{ number_format($st['totalRevenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="sp-stat-foot">{{ $st['totalTransactions'] ?? 0 }} nota transaksi</div>
    </div>
    <div class="sp-stat" style="border-left: 3px solid #7c3aed;">
        <div class="sp-stat-top"><span class="sp-stat-lbl">Non-Tunai</span><span class="sp-stat-badge">TF / EDC / QRIS</span></div>
        <div class="sp-stat-amt">Rp {{ number_format($st['nonCashRevenue'] ?? 0, 0, ',', '.') }}</div>
        <div class="sp-stat-foot">Transfer & elektronik</div>
    </div>
    <div class="sp-stat" style="border-left: 3px solid #10b981;">
        <div class="sp-stat-top"><span class="sp-stat-lbl">Durasi Sesi</span><span class="sp-stat-badge live">LIVE</span></div>
        <div class="sp-stat-amt" data-session-start="{{ optional($s->opened_at ?? $s->created_at)->toIso8601String() }}">--</div>
        <div class="sp-stat-foot">Sejak {{ optional($s->opened_at ?? $s->created_at)->format('H:i, d M Y') }}</div>
    </div>
</div>

{{-- 4. TUTUP KASIR --}}
@can('delete_sesi_kasir')
<div class="sp-close-card">
    <div class="sp-close-head">
        <div class="sp-close-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
        <div>
            <h3 class="sp-close-title">Tutup Kasir {{ $label }}</h3>
            <p class="sp-close-sub">Hitung uang fisik di laci, masukkan nominal, lalu konfirmasi penutupan.</p>
        </div>
    </div>
    <form method="POST" action="{{ route($closeRoute) }}">
        @csrf
        <input type="hidden" id="expected-{{ $type }}" value="{{ $st['expectedCash'] ?? 0 }}">
        <div class="sp-close-grid">
            <div class="sp-field">
                <label>Estimasi Sistem</label>
                <div class="sp-expected">Rp {{ number_format($st['expectedCash'] ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="sp-field">
                <label>Uang Fisik Laci (Aktual)</label>
                <div class="sp-input-wrap">
                    <span class="sp-input-prefix">Rp</span>
                    <input type="text" inputmode="numeric" data-currency name="actual_cash" id="actual-{{ $type }}" min="0" required placeholder="0" oninput="calcVariance('{{ $type }}')">
                </div>
            </div>
            <div class="sp-field">
                <label>Selisih (Variance)</label>
                <div class="sp-variance" id="var-box-{{ $type }}">
                    <span class="sp-var-amt" id="var-amt-{{ $type }}">Rp 0</span>
                    <span class="sp-var-badge" id="var-badge-{{ $type }}">Belum diisi</span>
                </div>
            </div>
        </div>
        <div class="sp-field" style="margin-top:0.75rem;">
            <label>Catatan Selisih (Opsional)</label>
            <input type="text" name="notes" class="sp-note-input" placeholder="Misal: selisih karena kembalian kurang...">
        </div>
        <div class="sp-close-action">
            <button type="submit" class="sp-close-btn" onclick="return confirmClose('{{ $type }}')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Konfirmasi &amp; Tutup Kasir
            </button>
        </div>
    </form>
</div>
@endcan

{{-- 5. MUTASI KAS --}}
@can('delete_sesi_kasir')
<div class="sp-mutasi">
    <h3 class="sp-section-title" style="margin-bottom:2px;">Mutasi Kas Manual — {{ $label }}</h3>
    <p class="sp-mutasi-desc">Catat pengeluaran/pemasukan laci di luar transaksi reguler.</p>
    <form method="POST" action="{{ route('kasir.cash_movement') }}" class="sp-mf">
        @csrf
        <input type="hidden" name="session_type" value="{{ $type }}">
        <div class="sp-mf-group">
            <label>Tipe</label>
            <select name="type" required>
                <option value="in">+ Masuk</option>
                <option value="out">− Keluar</option>
            </select>
        </div>
        <div class="sp-mf-group sp-mf-grow">
            <label>Nominal (Rp)</label>
            <input type="number" name="amount" min="1" step="1" required placeholder="0">
        </div>
        <div class="sp-mf-group sp-mf-grow2">
            <label>Keterangan</label>
            <input type="text" name="notes" placeholder="Misal: beli galon, kembalian...">
        </div>
        <div class="sp-mf-group">
            <button type="submit" class="sp-mf-btn">Catat</button>
        </div>
    </form>

    @if(isset($st['cashMovements']) && $st['cashMovements']->count())
        <div class="sp-mt-wrap">
            <table class="sp-mt">
                <thead>
                    <tr><th>Waktu</th><th class="tc">Tipe</th><th class="tr">Nominal</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @foreach($st['cashMovements'] as $m)
                        <tr>
                            <td class="sp-mt-time">{{ optional($m->created_at)->format('H:i, d M') }}</td>
                            <td class="tc">@if($m->type==='in')<span class="sp-tag-in">IN</span>@else<span class="sp-tag-out">OUT</span>@endif</td>
                            <td class="tr sp-mt-mono" style="font-weight:800;color:{{ $m->type==='in' ? '#16a34a' : '#dc2626' }}">
                                {{ $m->type==='in' ? '+' : '-' }}Rp {{ number_format((float)$m->amount, 0, ',', '.') }}
                            </td>
                            <td>{{ $m->notes ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="sp-mt-empty">Belum ada mutasi kas pada sesi ini.</div>
    @endif
</div>
@endcan
