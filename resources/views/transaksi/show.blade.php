<x-app-layout>
    <x-slot name="header">Detail Transaksi #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- ─── BREADCRUMB & HEADER ─── --}}
            <div class="tr-header animate-fade-in">
                <nav class="tr-breadcrumb">
                    <a href="{{ route('transaksi.index') }}" class="tr-back-link">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Riwayat Transaksi
                    </a>
                    <span class="tr-separator">/</span>
                    <span class="tr-current">Detail #{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
                </nav>

                <div class="tr-header-content">
                    <div class="tr-header-title">
                        <div class="tr-icon-box bg-indigo">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        <div>
                            <h1>Invoice <span class="tr-text-indigo">#{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span></h1>
                            <p>{{ $transaksi->created_at->format('l, d F Y — H:i:s') }} WIB</p>
                        </div>
                    </div>
                    <div class="tr-header-status">
                        @if($transaksi->status === 'completed')
                            <span class="tr-badge-lg badge-success">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                SELESAI
                            </span>
                        @elseif($transaksi->status === 'voided')
                            <span class="tr-badge-lg badge-danger">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                VOID (DIBATALKAN)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="tr-alert tr-alert-success animate-fade-in-up">✅ {{ session('success') }}</div>
            @endif

            {{-- ─── MAIN LAYOUT GRID ─── --}}
            <div class="tr-layout-grid animate-fade-in-up" style="animation-delay: 0.1s;">
                
                {{-- KIRI: Informasi Transaksi & Item --}}
                <div class="tr-left-col">
                    
                    {{-- Kotak Info Meta --}}
                    <div class="tr-meta-box">
                        <div class="meta-item">
                            <span class="meta-label">Kasir Bertugas</span>
                            <span class="meta-value">{{ $transaksi->user?->name ?? 'Sistem' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Metode Pembayaran</span>
                            <span class="meta-value tr-flex-align">
                                @if($transaksi->payment_method === 'cash')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="6" width="20" height="12" rx="2"></rect><circle cx="12" cy="12" r="2"></circle><path d="M6 12h.01M18 12h.01"></path></svg> Tunai
                                @elseif($transaksi->payment_method === 'transfer')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg> Transfer Bank
                                @elseif($transaksi->payment_method === 'qris')
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg> QRIS
                                @else
                                    {{ strtoupper($transaksi->payment_method) }}
                                @endif
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Jumlah Item</span>
                            <span class="meta-value">{{ $transaksi->details->count() }} Produk</span>
                        </div>
                        @if($transaksi->payment_method === 'transfer' && $transaksi->payment_reference)
                            <div class="meta-item">
                                <span class="meta-label">Ref / No. Referensi</span>
                                <span class="meta-value tr-font-mono">{{ $transaksi->payment_reference }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Tabel Detail Item --}}
                    <div class="tr-card">
                        <div class="tr-card-header">
                            <h2 class="tr-section-title">Detail Pembelian Barang</h2>
                        </div>
                        <div class="table-responsive">
                            <table class="tr-table-items">
                                <thead>
                                    <tr>
                                        <th class="c" style="width: 40px;">#</th>
                                        <th>Item Produk</th>
                                        <th class="r">Harga Satuan</th>
                                        <th class="c">Qty</th>
                                        <th class="r">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi->details as $i => $d)
                                        <tr class="{{ $transaksi->status === 'voided' ? 'is-voided' : '' }}">
                                            <td class="c text-muted">{{ $i + 1 }}</td>
                                            <td>
                                                <div class="item-name">{{ $d->product?->name ?? 'Produk Dihapus' }}</div>
                                                <div class="item-cat">{{ $d->product?->category?->name ?? 'Tanpa Kategori' }} &middot; {{ $d->product?->sku ?? '' }}</div>
                                            </td>
                                            <td class="r tr-font-mono">Rp {{ number_format($d->price, 0, ',', '.') }}</td>
                                            <td class="c tr-font-bold">x{{ $d->quantity }}</td>
                                            <td class="r tr-font-mono tr-font-bold text-main">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- KANAN: Ringkasan Pembayaran & Aksi --}}
                <div class="tr-right-col">
                    
                    {{-- Struk Kalkulasi --}}
                    <div class="tr-card tr-receipt-card">
                        <div class="receipt-header">Ringkasan Transaksi</div>
                        
                        <div class="receipt-body">
                            <div class="receipt-row">
                                <span class="r-label">Subtotal Item</span>
                                <span class="r-value tr-font-mono">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            {{-- Jika ada diskon/pajak bisa ditambahkan di sini nanti --}}
                            
                            <div class="receipt-divider"></div>
                            
                            <div class="receipt-row total-row">
                                <span class="r-label">Total Tagihan</span>
                                <span class="r-value tr-font-mono text-indigo">Rp {{ number_format($transaksi->total_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="receipt-row mt-2">
                                <span class="r-label">Jumlah Dibayar</span>
                                <span class="r-value tr-font-mono text-emerald">Rp {{ number_format($transaksi->paid_amount, 0, ',', '.') }}</span>
                            </div>
                            
                            <div class="receipt-row change-row">
                                <span class="r-label">Uang Kembali</span>
                                <span class="r-value tr-font-mono text-amber">Rp {{ number_format($transaksi->change_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="tr-actions-panel">
                        @if($transaksi->status === 'completed')
                            <a href="{{ route('print.receipt', $transaksi->id) }}" target="_blank" class="tr-btn tr-btn-indigo tr-btn-block">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                                Cetak Ulang Struk
                            </a>
                            
                            <div class="tr-divider-text">Aksi Manajemen</div>

                            @can('edit_transaksi')
                                <a href="{{ route('transaksi.retur.create', $transaksi) }}" class="tr-btn tr-btn-outline tr-btn-block">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                    Proses Retur Barang
                                </a>
                            @endcan

                            <form action="{{ route('transaksi.void', $transaksi) }}" method="POST" onsubmit="return confirm('PERINGATAN!\nVoid transaksi akan membatalkan struk ini secara permanen dan mengembalikan stok barang ke sistem.\nLanjutkan?')" class="tr-form-block">
                                @csrf @method('PATCH')
                                <button type="submit" class="tr-btn tr-btn-danger-outline tr-btn-block">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    Void (Batalkan Transaksi)
                                </button>
                            </form>
                        @elseif($transaksi->status === 'voided')
                            <div class="tr-void-notice">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                                <p>Transaksi ini telah dibatalkan (VOID). Stok barang telah dikembalikan ke sistem inventori.</p>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');

        :root {
            --tr-indigo: #4f46e5; --tr-indigo-hover: #4338ca; --tr-indigo-light: #e0e7ff;
            --tr-emerald: #10b981; --tr-emerald-light: #dcfce7;
            --tr-danger: #ef4444; --tr-danger-light: #fee2e2;
            --tr-amber: #f59e0b; --tr-amber-light: #fef3c7;
            --tr-bg: #f8fafc; --tr-surface: #ffffff; --tr-border: #e2e8f0;
            --tr-text-main: #0f172a; --tr-text-muted: #64748b;
            --tr-radius: 16px;
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); padding-bottom: 4rem; }
        .tr-page { max-width: 1100px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* ── ANIMATIONS ── */
        .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.4s ease forwards; opacity: 0; transform: translateY(10px); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }

        /* ── HEADER ── */
        .tr-breadcrumb { display: flex; align-items: center; gap: 8px; margin-bottom: 1.5rem; }
        .tr-back-link { display: flex; align-items: center; gap: 4px; text-decoration: none; color: var(--tr-text-muted); font-size: 0.85rem; font-weight: 700; transition: 0.2s; }
        .tr-back-link:hover { color: var(--tr-indigo); }
        .tr-separator { color: #cbd5e1; font-size: 0.85rem; }
        .tr-current { font-size: 0.85rem; font-weight: 600; color: var(--tr-text-main); }

        .tr-header-content { display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem; margin-bottom: 2rem; }
        .tr-header-title { display: flex; align-items: center; gap: 1rem; }
        .tr-icon-box { width: 54px; height: 54px; border-radius: 14px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .bg-indigo { background: var(--tr-indigo-light); color: var(--tr-indigo); }
        .tr-header-title h1 { font-size: 1.75rem; font-weight: 900; margin: 0 0 4px; letter-spacing: -0.02em; }
        .tr-text-indigo { color: var(--tr-indigo); font-family: ui-monospace, monospace; }
        .tr-header-title p { font-size: 0.9rem; color: var(--tr-text-muted); margin: 0; font-weight: 500; }

        .tr-badge-lg { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 99px; font-size: 0.85rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; }
        .badge-success { background: var(--tr-emerald-light); color: #065f46; }
        .badge-danger { background: var(--tr-danger-light); color: #991b1b; }

        .tr-alert { padding: 1rem 1.25rem; border-radius: 10px; margin-bottom: 1.5rem; font-weight: 600; font-size: 0.9rem; }
        .tr-alert-success { background: var(--tr-emerald-light); color: #065f46; border: 1px solid #a7f3d0; }

        /* ── MAIN LAYOUT GRID ── */
        .tr-layout-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }

        /* ── LEFT COLUMN ── */
        .tr-meta-box { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 1rem; background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); padding: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .meta-item { display: flex; flex-direction: column; gap: 4px; border-left: 3px solid var(--tr-indigo-light); padding-left: 10px; }
        .meta-label { font-size: 0.7rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
        .meta-value { font-size: 0.95rem; font-weight: 800; color: var(--tr-text-main); }
        .tr-flex-align { display: inline-flex; align-items: center; gap: 6px; }

        .tr-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius); box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafafa; }
        .tr-section-title { font-size: 1rem; font-weight: 800; margin: 0; color: var(--tr-text-main); text-transform: uppercase; letter-spacing: 0.025em; }
        
        .table-responsive { width: 100%; overflow-x: auto; }
        .tr-table-items { width: 100%; border-collapse: collapse; min-width: 500px; }
        .tr-table-items thead th { background: #f8fafc; padding: 0.85rem 1rem; text-align: left; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: var(--tr-text-muted); border-bottom: 1px solid var(--tr-border); }
        .tr-table-items tbody td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        .tr-table-items tbody tr:last-child td { border-bottom: none; }
        
        .is-voided td { opacity: 0.5; text-decoration: line-through; background: #f8fafc; }
        
        .tr-table-items .c { text-align: center; }
        .tr-table-items .r { text-align: right; }
        
        .item-name { font-weight: 700; font-size: 0.95rem; color: var(--tr-text-main); margin-bottom: 2px; }
        .item-cat { font-size: 0.75rem; color: var(--tr-text-muted); font-weight: 600; }

        /* ── RIGHT COLUMN (RECEIPT) ── */
        .tr-receipt-card { background: #fff; position: relative; margin-bottom: 1.5rem; }
        /* Receipt Jagged Edge Effect */
        .tr-receipt-card::before { content: ""; position: absolute; top: -6px; left: 0; width: 100%; height: 6px; background: linear-gradient(135deg, transparent 33.33%, #fff 33.33%, #fff 66.66%, transparent 66.66%), linear-gradient(45deg, transparent 33.33%, #fff 33.33%, #fff 66.66%, transparent 66.66%); background-size: 12px 12px; }
        
        .receipt-header { padding: 1.5rem 1.5rem 1rem; font-size: 0.85rem; font-weight: 800; color: var(--tr-text-muted); text-transform: uppercase; letter-spacing: 0.05em; text-align: center; border-bottom: 1px dashed var(--tr-border); }
        .receipt-body { padding: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem; }
        .receipt-row { display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; }
        .r-label { color: var(--tr-text-muted); font-weight: 600; }
        .r-value { font-weight: 700; color: var(--tr-text-main); }
        
        .receipt-divider { border-top: 1px dashed var(--tr-border); margin: 0.5rem 0; }
        
        .total-row { font-size: 1.1rem; }
        .total-row .r-label { font-weight: 800; color: var(--tr-text-main); }
        .total-row .r-value { font-weight: 900; font-size: 1.25rem; }
        
        .mt-2 { margin-top: 0.5rem; }
        .change-row { background: var(--tr-bg); padding: 0.75rem 1rem; border-radius: 8px; margin-top: 0.5rem; }
        .change-row .r-label { font-weight: 800; color: var(--tr-text-main); }
        .change-row .r-value { font-weight: 900; font-size: 1.1rem; }

        /* ── ACTIONS ── */
        .tr-actions-panel { display: flex; flex-direction: column; gap: 0.75rem; }
        
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0.85rem 1.5rem; border-radius: 12px; font-size: 0.9rem; font-weight: 800; cursor: pointer; transition: 0.2s; border: 1px solid transparent; text-decoration: none; }
        .tr-btn-block { width: 100%; }
        
        .tr-btn-indigo { background: var(--tr-indigo); color: white; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
        .tr-btn-indigo:hover { background: var(--tr-indigo-hover); transform: translateY(-2px); }
        
        .tr-btn-outline { border-color: var(--tr-border); background: white; color: var(--tr-text-main); }
        .tr-btn-outline:hover { background: #f1f5f9; border-color: var(--tr-text-muted); }
        
        .tr-btn-danger-outline { border-color: #fecaca; color: var(--tr-danger); background: transparent; }
        .tr-btn-danger-outline:hover { background: var(--tr-danger-light); }

        .tr-form-block { width: 100%; margin: 0; }

        .tr-divider-text { text-align: center; font-size: 0.75rem; font-weight: 700; color: var(--tr-text-muted); text-transform: uppercase; margin: 0.5rem 0; position: relative; }
        .tr-divider-text::before, .tr-divider-text::after { content: ''; position: absolute; top: 50%; width: 25%; height: 1px; background: var(--tr-border); }
        .tr-divider-text::before { left: 0; } .tr-divider-text::after { right: 0; }

        .tr-void-notice { background: var(--tr-danger-light); border: 1px solid #fecaca; padding: 1.25rem; border-radius: 12px; text-align: center; color: #991b1b; display: flex; flex-direction: column; align-items: center; gap: 8px; }
        .tr-void-notice p { margin: 0; font-size: 0.85rem; font-weight: 600; line-height: 1.5; }

        /* ── UTILS ── */
        .tr-font-mono { font-family: ui-monospace, SFMono-Regular, Consolas, monospace; }
        .tr-font-bold { font-weight: 800; }
        .text-main { color: var(--tr-text-main); }
        .text-muted { color: var(--tr-text-muted); }
        .text-emerald { color: var(--tr-emerald); }
        .text-indigo { color: var(--tr-indigo); }
        .text-amber { color: #d97706; }

        @media (max-width: 992px) {
            .tr-layout-grid { grid-template-columns: 1fr; }
            .tr-meta-box { grid-template-columns: repeat(2, 1fr); }
            .tr-header-content { flex-direction: column; }
        }
        @media (max-width: 640px) {
            .tr-meta-box { grid-template-columns: 1fr; }
        }
    </style>
    @endpush
</x-app-layout>