<x-app-layout>
    <x-slot name="header">Detail Transfer Stok</x-slot>

    <div class="tr-page-wrapper">
        <div class="tr-page">

            {{-- HEADER --}}
            <div class="tr-header">
                <div class="tr-header-text">
                    <div class="tr-eyebrow">Manajemen Gudang</div>
                    <h1 class="tr-title">
                        <div class="tr-title-icon-box bg-blue">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        </div>
                        Detail Transfer Stok
                    </h1>
                    <p class="tr-subtitle">Informasi lengkap proses perpindahan stok antar gudang atau lokasi.</p>
                </div>
                <div class="tr-header-actions">
                    <a href="{{ route('gudang.transfer') }}" class="tr-btn tr-btn-outline">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            {{-- GRID LAYOUT --}}
            <div class="tr-grid-2">
                
                {{-- KOLOM KIRI: Informasi Umum --}}
                <div class="tr-card-col">
                    <div class="tr-card">
                        <div class="tr-card-header">
                            <h2 class="tr-card-title">Informasi Umum</h2>
                        </div>
                        <div class="tr-info-list">
                            <div class="tr-info-row">
                                <span class="tr-info-label">No. Referensi</span>
                                <span class="tr-info-value tr-font-bold tr-text-primary tr-font-mono tr-font-bold">{{ $transfer->reference_number }}</span>
                            </div>
                            <div class="tr-info-row">
                                <span class="tr-info-label">Tanggal / Waktu</span>
                                <span class="tr-info-value tr-font-semibold">{{ optional($summary->created_at)->format('d M Y · H:i:s') ?? '-' }}</span>
                            </div>
                            <div class="tr-info-row">
                                <span class="tr-info-label">Ringkasan Barang</span>
                                <span class="tr-info-value tr-font-bold">
                                    <span class="tr-pill-light">{{ $summary->total_products }} produk</span>
                                    <span class="tr-dot-divider">•</span>
                                    <span class="tr-pill-light">{{ $summary->total_items }} baris</span>
                                    <span class="tr-dot-divider">•</span>
                                    Total Qty: <span class="tr-text-primary">{{ $summary->total_qty }}</span>
                                    @if($summary->unit_name && $summary->unit_name !== 'satuan dasar')
                                        <span class="tr-unit-badge" style="margin-left: 8px;">{{ $summary->unit_name }}</span>
                                        <div class="tr-conversion-text" style="margin-top: 4px;">
                                            {{ $summary->total_qty_in_unit }} {{ $summary->unit_name }} = {{ $summary->total_qty }} satuan dasar
                                        </div>
                                    @endif
                                </span>
                            </div>
                            <div class="tr-info-row">
                                <span class="tr-info-label">Dicatat Oleh</span>
                                <span class="tr-info-value tr-font-semibold">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right:4px; color:var(--tr-text-light);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    {{ $summary->user?->name ?? 'Sistem' }}
                                </span>
                            </div>
                            <div class="tr-info-row tr-info-notes">
                                <span class="tr-info-label">Catatan Transfer</span>
                                <div class="tr-info-value">
                                    @if($summary->notes_preview->isNotEmpty())
                                        <div class="tr-notes-box">
                                            {{ $summary->notes_preview->first() }}
                                            @if($summary->notes_preview->count() > 1)
                                                <div class="tr-notes-more">+{{ $summary->notes_preview->count() - 1 }} catatan item lainnya</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="tr-text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: Rincian Batch & Lokasi --}}
                <div class="tr-card-col">
                    <h2 class="tr-section-title">Rincian Batch & Lokasi</h2>
                    
                    <div class="tr-batch-list">
                        @foreach($outs as $idx => $out)
                            @php
                                $in = $ins->first(function($candidate) use ($out) {
                                    return (int)$candidate->product_id === (int)$out->product_id
                                        && (string)$candidate->batch_number === (string)$out->batch_number
                                        && (string)$candidate->expired_date === (string)$out->expired_date
                                        && (int)$candidate->quantity === (int)$out->quantity;
                                }) ?? $ins->firstWhere('product_id', $out->product_id) ?? $ins->get($idx);
                            @endphp
                            
                            <div class="tr-batch-card">
                                {{-- Header Produk --}}
                                <div class="tr-batch-header">
                                    <div class="tr-batch-prod">
                                        <span class="tr-prod-name">{{ $out->product?->name ?? 'Produk Dihapus' }}</span>
                                        <span class="tr-prod-qty">Qty: {{ $out->quantity }}</span>
                                    </div>
                                    <div class="tr-batch-meta">
                                        <span class="tr-meta-chip">Batch: {{ $out->batch_number ?: 'Tanpa Batch' }}</span>
                                        @if($out->expired_date)
                                            <span class="tr-meta-chip tr-chip-warning">ED: {{ \Carbon\Carbon::parse($out->expired_date)->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Catatan Item Khusus --}}
                                @if($out->notes)
                                    <div class="tr-item-notes">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                                        {{ $out->notes }}
                                    </div>
                                @endif

                                {{-- Flow Diagram (Asal -> Tujuan) --}}
                                <div class="tr-flow-container">
                                    {{-- Asal --}}
                                    <div class="tr-flow-box box-origin">
                                        <div class="tr-flow-label text-danger">KELUAR DARI</div>
                                        <div class="tr-flow-wh">{{ $out->warehouse?->name ?? '-' }}</div>
                                        <div class="tr-flow-loc">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            {{ $out->location?->name ?? 'Tanpa Rak' }}
                                        </div>
                                    </div>
                                    
                                    {{-- Arrow --}}
                                    <div class="tr-flow-arrow">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                                    </div>

                                    {{-- Tujuan --}}
                                    <div class="tr-flow-box box-dest">
                                        <div class="tr-flow-label text-success">MASUK KE</div>
                                        <div class="tr-flow-wh">{{ $in?->warehouse?->name ?? '-' }}</div>
                                        <div class="tr-flow-loc">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                            {{ $in?->location?->name ?? 'Tanpa Rak' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- FOOTER ACTIONS --}}
            <div class="tr-page-footer">
                <form action="{{ route('gudang.transfer.destroy', $transfer) }}" method="POST" onsubmit="return confirm('Peringatan: Yakin menghapus riwayat transfer ini? Stok akan otomatis dikembalikan ke lokasi asal.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="tr-btn tr-btn-danger">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                        Batalkan & Hapus Transfer
                    </button>
                </form>
            </div>

        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        :root {
            --tr-bg: #f8fafc;
            --tr-surface: #ffffff;
            --tr-border: #e2e8f0;
            --tr-border-light: #f1f5f9;
            --tr-text-main: #0f172a;
            --tr-text-muted: #64748b;
            --tr-text-light: #94a3b8;
            --tr-primary: #3b82f6;
            --tr-primary-light: #eff6ff;
            --tr-danger: #ef4444;
            --tr-danger-hover: #dc2626;
            --tr-danger-light: #fef2f2;
            --tr-success: #10b981;
            --tr-success-light: #ecfdf5;
            --tr-warning-text: #b45309;
            --tr-warning-bg: #fffbeb;
            --tr-radius-lg: 12px;
            --tr-radius-md: 8px;
            --tr-shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tr-shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05);
        }

        .tr-page-wrapper { background-color: var(--tr-bg); min-height: 100vh; padding-bottom: 3rem; }
        .tr-page { padding: 2rem 1.5rem; max-width: 1080px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--tr-text-main); }

        /* ── HEADER ── */
        .tr-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
        .tr-eyebrow { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--tr-primary); margin-bottom: 0.35rem; }
        .tr-title { font-size: 1.5rem; font-weight: 800; color: var(--tr-text-main); margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: -0.02em; }
        .tr-title-icon-box { display: flex; align-items: center; justify-content: center; padding: 6px; border-radius: 8px; }
        .tr-title-icon-box.bg-blue { background: var(--tr-primary-light); color: var(--tr-primary); }
        .tr-subtitle { font-size: 0.85rem; color: var(--tr-text-muted); margin: 0.35rem 0 0 0; }
        
        .tr-header-actions { display: flex; gap: 0.5rem; }

        /* ── BUTTONS ── */
        .tr-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 0.55rem 1.1rem; border-radius: 6px; font-size: 0.85rem; font-family: inherit; font-weight: 600; cursor: pointer; white-space: nowrap; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .tr-btn-outline { border-color: var(--tr-border); color: var(--tr-text-muted); background: var(--tr-surface); box-shadow: var(--tr-shadow-sm); }
        .tr-btn-outline:hover { border-color: var(--tr-text-light); color: var(--tr-text-main); }
        .tr-btn-danger { background: var(--tr-danger); color: #ffffff; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2); }
        .tr-btn-danger:hover { background: var(--tr-danger-hover); transform: translateY(-1px); box-shadow: 0 4px 6px rgba(239, 68, 68, 0.3); }

        /* ── GRID LAYOUT ── */
        .tr-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.75rem; }
        .tr-section-title { font-size: 1.05rem; font-weight: 700; color: var(--tr-text-main); margin: 0 0 1rem 0; padding-bottom: 0.75rem; border-bottom: 1px solid var(--tr-border); }

        /* ── CARD (INFO UMUM) ── */
        .tr-card { background: var(--tr-surface); border-radius: var(--tr-radius-lg); border: 1px solid var(--tr-border); box-shadow: var(--tr-shadow-sm); overflow: hidden; }
        .tr-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--tr-border-light); background: #ffffff; }
        .tr-card-title { font-size: 1rem; font-weight: 700; color: var(--tr-text-main); margin: 0; }
        
        .tr-info-list { padding: 0.5rem 1.5rem 1.5rem; }
        .tr-info-row { display: flex; padding: 1rem 0; border-bottom: 1px dashed var(--tr-border-light); align-items: center; gap: 1rem; }
        .tr-info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .tr-info-row.tr-info-notes { align-items: flex-start; }
        
        .tr-info-label { width: 140px; font-size: 0.85rem; color: var(--tr-text-muted); flex-shrink: 0; }
        .tr-info-value { font-size: 0.85rem; color: var(--tr-text-main); flex: 1; display: flex; align-items: center; flex-wrap: wrap; gap: 4px; }
        
        /* Typography Helpers */
        .tr-text-primary { color: var(--tr-primary); }
        .tr-text-muted { color: var(--tr-text-light); }
        .tr-font-bold { font-weight: 700; }
        .tr-font-semibold { font-weight: 600; }
        .tr-font-mono { font-family: monospace; font-size: 0.95rem; }
        
        .tr-pill-light { background: var(--tr-border-light); padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; color: var(--tr-text-muted); }
        .tr-dot-divider { color: var(--tr-border); font-size: 1rem; }

        .tr-notes-box { background: var(--tr-bg); border: 1px solid var(--tr-border); padding: 0.6rem 0.8rem; border-radius: var(--tr-radius-md); width: 100%; font-size: 0.8rem; line-height: 1.5; color: var(--tr-text-muted); }
        .tr-notes-more { font-size: 0.75rem; color: var(--tr-text-light); margin-top: 4px; font-style: italic; }

        /* ── BATCH CARDS (KANAN) ── */
        .tr-batch-list { display: flex; flex-direction: column; gap: 1rem; }
        .tr-batch-card { background: var(--tr-surface); border: 1px solid var(--tr-border); border-radius: var(--tr-radius-lg); padding: 1.25rem; box-shadow: var(--tr-shadow-sm); }
        
        .tr-batch-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 1rem; margin-bottom: 1rem; flex-wrap: wrap; }
        .tr-batch-prod { display: flex; flex-direction: column; gap: 2px; }
        .tr-prod-name { font-weight: 700; color: var(--tr-text-main); font-size: 0.95rem; line-height: 1.2; }
        .tr-prod-qty { font-size: 0.8rem; color: var(--tr-primary); font-weight: 800; background: var(--tr-primary-light); display: inline-block; padding: 2px 8px; border-radius: 4px; width: fit-content; margin-top: 4px; }
        
        .tr-batch-meta { display: flex; gap: 6px; flex-wrap: wrap; }
        .tr-meta-chip { background: var(--tr-bg); border: 1px solid var(--tr-border); font-family: monospace; font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; color: var(--tr-text-muted); font-weight: 600; }
        .tr-chip-warning { background: var(--tr-warning-bg); border-color: #fde68a; color: var(--tr-warning-text); }

        .tr-item-notes { font-size: 0.75rem; color: #475569; margin-bottom: 1rem; background: var(--tr-primary-light); border: 1px solid #e0e7ff; padding: 0.5rem 0.75rem; border-radius: 6px; display: flex; gap: 8px; align-items: flex-start; line-height: 1.4; }
        .tr-item-notes svg { color: var(--tr-primary); flex-shrink: 0; margin-top: 1px; }

        /* Flow Diagram */
        .tr-flow-container { display: flex; align-items: center; gap: 1rem; background: var(--tr-bg); padding: 1rem; border-radius: var(--tr-radius-md); border: 1px solid var(--tr-border-light); }
        .tr-flow-box { flex: 1; display: flex; flex-direction: column; gap: 2px; }
        .tr-flow-arrow { color: var(--tr-text-light); flex-shrink: 0; }
        
        .tr-flow-label { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.05em; margin-bottom: 4px; }
        .text-danger { color: var(--tr-danger); }
        .text-success { color: var(--tr-success); }
        
        .tr-flow-wh { font-weight: 700; color: var(--tr-text-main); font-size: 0.85rem; }
        .tr-flow-loc { font-size: 0.75rem; color: var(--tr-text-muted); display: flex; align-items: center; gap: 4px; font-weight: 500; }
        .tr-flow-loc svg { color: var(--tr-text-light); }

        /* ── FOOTER ── */
        .tr-page-footer { margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid var(--tr-border); display: flex; justify-content: flex-end; }

        /* ── RESPONSIVE ── */
        @media (max-width: 820px) {
            .tr-header { flex-direction: column; align-items: flex-start; }
            .tr-grid-2 { grid-template-columns: 1fr; }
            .tr-info-label { width: 120px; }
        }
        @media (max-width: 480px) {
            .tr-info-row { flex-direction: column; align-items: flex-start; gap: 4px; }
            .tr-info-label { width: 100%; font-size: 0.75rem; margin-bottom: 2px; }
            .tr-flow-container { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
            .tr-flow-arrow { transform: rotate(90deg); padding-left: 0.5rem; }
        }
    </style>
    @endpush
</x-app-layout>