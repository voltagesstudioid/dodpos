<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir Eceran — DODPOS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        :root{
            --bg:#f1f5f9;--surface:#ffffff;--text:#0f172a;--muted:#64748b;--border:#e2e8f0;
            --accent:#10b981;--accent-dark:#059669;--accent-light:#ecfdf5;
            --green:#10b981;--green-light:#dcfce7;--green-dark:#059669;
            --red:#ef4444;--red-light:#fee2e2;--red-dark:#dc2626;
            --amber:#f59e0b;--amber-light:#fef3c7;
            --radius:12px;--radius-lg:16px;
        }
        body{font-family:'Inter',system-ui,sans-serif;background:var(--bg);color:var(--text);height:100vh;display:flex;flex-direction:column;overflow:hidden}

        /* ── TOPBAR ── */
        .topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 1.25rem;height:56px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
        .topbar-left{display:flex;align-items:center;gap:0.75rem}
        .topbar-right{display:flex;align-items:center;gap:1rem;font-size:0.8rem;color:var(--muted)}
        .btn-back{display:inline-flex;align-items:center;gap:4px;background:#f8fafc;border:1px solid var(--border);color:#334155;padding:0.4rem 0.85rem;border-radius:8px;font-size:0.8rem;font-weight:700;text-decoration:none;transition:0.15s;font-family:inherit}
        .btn-back:hover{background:#e2e8f0;border-color:#cbd5e1}
        .pos-badge{background:var(--accent);color:#fff;padding:0.25rem 0.75rem;border-radius:6px;font-size:0.7rem;font-weight:800;letter-spacing:0.05em}
        .topbar-title{font-size:0.95rem;font-weight:800;color:var(--text)}
        .topbar-pill{font-size:0.68rem;padding:0.2rem 0.6rem;border-radius:6px;border:1px solid #bbf7d0;color:#065f46;background:#ecfdf5;font-weight:700}

        /* ── LAYOUT ── */
        .pos-layout{display:grid;grid-template-columns:1fr 360px;flex:1;overflow:hidden}
        .product-panel{display:flex;flex-direction:column;overflow:hidden}

        /* ── SEARCH ── */
        .search-bar{padding:0.75rem 1rem;background:var(--surface);border-bottom:1px solid var(--border);flex-shrink:0}
        .search-wrap{position:relative}
        .search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none}
        .search-input{width:100%;background:#f8fafc;border:1.5px solid var(--border);border-radius:10px;padding:0.65rem 1rem 0.65rem 2.5rem;color:var(--text);font-size:0.85rem;outline:none;font-family:inherit;transition:0.15s}
        .search-input:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .search-input::placeholder{color:#94a3b8}

        /* ── PRODUCT GRID ── */
        .product-grid{flex:1;overflow-y:auto;padding:0.875rem;display:grid;grid-template-columns:repeat(auto-fill,minmax(170px,1fr));gap:0.625rem;align-content:start}
        .product-grid::-webkit-scrollbar{width:5px}
        .product-grid::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:99px}

        /* ── PRODUCT CARD ── */
        .product-card{background:var(--surface);border:1.5px solid var(--border);border-radius:var(--radius);padding:0.85rem;cursor:pointer;transition:all 0.15s;position:relative;display:flex;flex-direction:column}
        .product-card:hover{border-color:var(--accent);box-shadow:0 4px 12px rgba(16,185,129,0.08);transform:translateY(-1px)}
        .product-card:active{transform:scale(0.98)}
        .prod-stock{position:absolute;top:8px;right:8px;font-size:0.6rem;font-weight:800;padding:2px 6px;border-radius:6px;background:var(--green-light);color:var(--green-dark)}
        .prod-stock.low{background:var(--amber-light);color:#92400e}
        .prod-name{font-size:0.8rem;font-weight:800;color:var(--text);line-height:1.3;margin-bottom:2px;padding-right:32px}
        .prod-cat{font-size:0.65rem;color:var(--muted);margin-bottom:0.5rem}
        .prod-units-list{display:flex;flex-direction:column;gap:2px;margin-top:auto}
        .prod-unit-row{display:flex;justify-content:space-between;align-items:center;font-size:0.68rem;padding:2px 0}
        .prod-unit-name{color:var(--accent-dark);font-weight:700}
        .prod-unit-price{color:var(--text);font-weight:800;font-family:ui-monospace,monospace;font-size:0.7rem}
        .prod-nostock{opacity:0.4;cursor:not-allowed;pointer-events:none}
        .prod-nostock:hover{transform:none;border-color:var(--border);box-shadow:none}
        .prod-nostock .prod-stock{background:var(--red-light);color:var(--red-dark)}

        /* ── CART PANEL ── */
        .cart-panel{background:var(--surface);border-left:1px solid var(--border);display:flex;flex-direction:column;overflow:hidden}
        .cart-header{padding:0.75rem 1rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;flex-shrink:0}
        .cart-title{font-size:0.85rem;font-weight:800;display:flex;align-items:center;gap:8px}
        .cart-count{background:var(--accent);color:#fff;border-radius:6px;padding:1px 7px;font-size:0.65rem;font-weight:800}
        .btn-clear{background:var(--red-light);border:none;color:var(--red-dark);padding:0.3rem 0.65rem;border-radius:6px;font-size:0.7rem;font-weight:800;cursor:pointer;font-family:inherit;transition:0.15s}
        .btn-clear:hover{background:#fecaca}

        /* ── CART CONFIG ── */
        .cart-config{padding:0.625rem 1rem;border-bottom:1px solid var(--border);display:flex;flex-direction:column;gap:0.5rem;flex-shrink:0}
        .config-select{width:100%;padding:0.5rem 0.7rem;background:#f8fafc;border:1.5px solid var(--border);border-radius:8px;color:var(--text);font-size:0.78rem;font-weight:600;outline:none;font-family:inherit;transition:0.15s;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2364748b' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10z'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 10px center}
        .config-select:focus{border-color:var(--accent);background-color:#fff;box-shadow:0 0 0 3px rgba(16,185,129,0.08)}
        .customer-info{display:none;margin-top:0;padding:0.6rem;background:#f8fafc;border-radius:8px;border:1px solid var(--border);font-size:0.7rem}
        .ci-row{display:flex;justify-content:space-between;margin-bottom:3px}
        .ci-label{color:var(--muted);font-weight:600}
        .ci-value{color:var(--text);font-weight:800}
        #cDebt{color:#b91c1c}
        #cRem{color:#15803d}
        .ci-divider{border-top:1px dashed var(--border);padding-top:3px;margin-top:3px}

        /* ── CART ITEMS ── */
        .cart-items{flex:1;overflow-y:auto;padding:0.625rem 0.75rem}
        .cart-items::-webkit-scrollbar{width:4px}
        .cart-items::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:99px}
        .cart-empty{text-align:center;padding:3rem 1rem;color:#94a3b8;font-size:0.85rem}
        .cart-item{background:#f8fafc;border:1px solid var(--border);border-radius:10px;padding:0.7rem;margin-bottom:0.5rem}
        .ci-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:0.4rem}
        .ci-name{font-size:0.78rem;font-weight:800;color:var(--text);flex:1;line-height:1.3}
        .ci-remove{background:none;border:none;color:#94a3b8;cursor:pointer;font-size:1rem;padding:0 2px;transition:0.15s}
        .ci-remove:hover{color:var(--red)}
        .ci-warehouse{width:100%;padding:0.3rem 0.5rem;background:#fff;border:1px solid var(--border);border-radius:6px;font-size:0.68rem;color:var(--muted);font-family:inherit;margin-bottom:0.4rem;outline:none}
        .ci-bottom{display:flex;align-items:center;gap:0.4rem}
        .unit-select{flex:1;background:#fff;border:1px solid var(--border);color:var(--text);border-radius:6px;padding:0.35rem 0.5rem;font-size:0.68rem;font-weight:700;cursor:pointer;font-family:inherit;min-width:0}
        .unit-select:focus{border-color:var(--accent)}
        .qty-ctrl{display:flex;align-items:center;gap:2px;flex-shrink:0}
        .qty-btn{width:24px;height:24px;background:#e2e8f0;border:none;color:#334155;border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:800;transition:0.15s}
        .qty-btn:hover{background:var(--accent-light);color:var(--accent-dark)}
        .qty-num{min-width:22px;text-align:center;font-weight:800;font-size:0.8rem}
        .ci-subtotal{font-size:0.75rem;font-weight:800;color:var(--accent-dark);white-space:nowrap;font-family:ui-monospace,monospace}

        /* ── CART FOOTER ── */
        .cart-footer{border-top:1px solid var(--border);padding:0.875rem 1rem;flex-shrink:0}
        .summary-row{display:flex;justify-content:space-between;font-size:0.72rem;color:var(--muted);margin-bottom:4px;font-weight:600}
        .summary-total{display:flex;justify-content:space-between;font-size:1.15rem;font-weight:900;color:var(--text);margin:0.5rem 0 0.75rem;font-family:ui-monospace,monospace}
        .btn-pay{width:100%;background:var(--accent);border:none;color:#fff;padding:0.8rem;border-radius:10px;font-size:0.9rem;font-weight:800;cursor:pointer;font-family:inherit;transition:0.15s;display:flex;align-items:center;justify-content:center;gap:6px}
        .btn-pay:hover{background:var(--accent-dark);transform:translateY(-1px);box-shadow:0 4px 12px rgba(16,185,129,0.25)}
        .btn-pay:disabled{opacity:0.3;cursor:not-allowed;transform:none;box-shadow:none}

        /* ── MODAL ── */
        .modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,0.5);z-index:1000;display:none;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(4px)}
        .modal-overlay.show{display:flex}
        .modal{background:var(--surface);border-radius:var(--radius-lg);padding:1.5rem;width:100%;max-width:440px;max-height:90vh;overflow-y:auto;border:1px solid var(--border);box-shadow:0 24px 48px rgba(0,0,0,0.18)}
        .modal::-webkit-scrollbar{width:4px}
        .modal::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:99px}
        .modal-title{font-size:1.05rem;font-weight:800;margin-bottom:1rem;display:flex;align-items:center;gap:8px}
        .modal-total-box{background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-radius:var(--radius);padding:1rem;text-align:center;margin-bottom:1rem;border:1px solid #86efac}
        .modal-total-label{font-size:0.68rem;color:#065f46;font-weight:700;text-transform:uppercase;letter-spacing:0.06em}
        .modal-total-amount{font-size:1.85rem;font-weight:900;color:#064e3b;font-family:ui-monospace,monospace;margin-top:4px}
        .form-label{font-size:0.7rem;font-weight:700;color:var(--muted);margin-bottom:4px;display:block;text-transform:uppercase;letter-spacing:0.03em}
        .form-input{width:100%;background:#f8fafc;border:1.5px solid var(--border);border-radius:8px;padding:0.65rem 0.85rem;color:var(--text);font-size:0.95rem;font-family:inherit;outline:none;transition:0.15s}
        .form-input:focus{border-color:var(--accent);background:#fff;box-shadow:0 0 0 3px rgba(16,185,129,0.1)}
        .pay-methods{display:grid;grid-template-columns:repeat(4,1fr);gap:0.4rem;margin-bottom:0.875rem}
        .pay-method{background:#f8fafc;border:1.5px solid var(--border);border-radius:8px;padding:0.55rem;text-align:center;cursor:pointer;font-size:0.72rem;font-weight:800;transition:0.15s;color:#475569}
        .pay-method:hover{border-color:#94a3b8}
        .pay-method.active{border-color:var(--accent);color:var(--accent-dark);background:var(--accent-light)}
        .change-box{background:linear-gradient(135deg,#fef3c7,#fde68a);border-radius:10px;padding:0.85rem;text-align:center;margin:0.75rem 0;border:1px solid #fcd34d}
        .change-label{font-size:0.68rem;color:#92400e;font-weight:800;text-transform:uppercase;letter-spacing:0.06em}
        .change-amount{font-size:1.5rem;font-weight:900;color:#b45309;font-family:ui-monospace,monospace;margin-top:2px}
        .quick-cash{display:flex;flex-wrap:wrap;gap:0.35rem;margin-top:0.5rem}
        .quick-btn{background:#f1f5f9;border:1px solid var(--border);color:#334155;padding:0.3rem 0.7rem;border-radius:6px;cursor:pointer;font-size:0.72rem;font-weight:700;font-family:inherit;transition:0.15s}
        .quick-btn:hover{background:var(--accent-light);border-color:var(--accent);color:var(--accent-dark)}
        .quick-btn.pas{background:var(--green-light);border-color:#86efac;color:var(--green-dark);font-weight:800}
        .quick-btn.pas:hover{background:#bbf7d0;border-color:var(--green)}
        .debt-box{background:var(--red-light);border-radius:10px;padding:0.75rem;text-align:center;margin:0.75rem 0;border:1px solid #fecaca}
        .debt-label{font-size:0.65rem;color:#991b1b;font-weight:700;text-transform:uppercase;letter-spacing:0.05em}
        .debt-amount{font-size:1.4rem;font-weight:900;color:var(--red);font-family:ui-monospace,monospace;margin-top:2px}
        .modal-actions{display:flex;gap:0.625rem;margin-top:1rem}
        .btn-confirm{flex:2;background:var(--accent);border:none;color:#fff;padding:0.8rem;border-radius:10px;font-size:0.9rem;font-weight:800;cursor:pointer;font-family:inherit;transition:0.15s}
        .btn-confirm:hover{background:var(--accent-dark)}
        .btn-confirm:disabled{opacity:0.5;cursor:not-allowed}
        .btn-cancel{flex:1;background:#f1f5f9;border:1px solid var(--border);color:#334155;padding:0.8rem;border-radius:10px;cursor:pointer;font-family:inherit;font-weight:800;font-size:0.85rem;transition:0.15s}
        .btn-cancel:hover{background:#e2e8f0}

        /* ── BANK DISPLAY ── */
        .bank-info{background:var(--accent-light);border:1px solid #86efac;border-radius:10px;padding:0.75rem;margin-bottom:0.75rem}
        .bank-info-title{font-weight:800;color:#065f46;font-size:0.72rem;margin-bottom:3px}
        .bank-info-detail{color:var(--text);font-weight:800;font-size:0.85rem;line-height:1.3}
        .bank-info-help{color:var(--muted);font-size:0.7rem;margin-top:3px}

        /* ── SUCCESS OVERLAY ── */
        .success-box{background:var(--surface);border-radius:var(--radius-lg);padding:2rem;width:100%;max-width:360px;border:1px solid var(--border);text-align:center;box-shadow:0 24px 48px rgba(0,0,0,0.15)}
        .success-icon{width:64px;height:64px;background:var(--green-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.75rem}
        .success-title{font-size:1.15rem;font-weight:800;color:var(--text);margin-bottom:0.25rem}
        .success-change-label{font-size:0.72rem;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:0.04em}
        .success-change-amount{font-size:1.75rem;font-weight:900;color:var(--amber);font-family:ui-monospace,monospace;margin:0.25rem 0 1rem}
        .btn-success{width:100%;border:none;padding:0.75rem;border-radius:10px;font-size:0.85rem;font-weight:800;cursor:pointer;font-family:inherit;transition:0.15s;display:flex;align-items:center;justify-content:center;gap:6px}
        .btn-print{background:var(--accent);color:#fff;margin-bottom:0.5rem}
        .btn-print:hover{background:var(--accent-dark)}
        .btn-new{background:#f1f5f9;color:#334155;border:1px solid var(--border)}
        .btn-new:hover{background:#e2e8f0}

        @media (max-width:980px){
            .pos-layout{grid-template-columns:1fr;grid-template-rows:1fr 1fr}
            .cart-panel{border-left:none;border-top:1px solid var(--border)}
            .product-grid{grid-template-columns:repeat(auto-fill,minmax(150px,1fr))}
            .pay-methods{grid-template-columns:repeat(2,1fr)}
        }
    </style>
</head>
<body>

{{-- TOPBAR --}}
<div class="topbar">
    <div class="topbar-left">
        <a href="{{ route('kasir.index') }}" class="btn-back">← Kembali</a>
        <span class="pos-badge">ECERAN</span>
        <span class="topbar-title">Kasir Eceran</span>
        <span class="topbar-pill">Multi Satuan</span>
    </div>
    <div class="topbar-right">
        <span id="clock"></span>
        <span style="font-weight:700;color:var(--text)">{{ Auth::user()->name }}</span>
    </div>
</div>

<div class="pos-layout">
    {{-- PRODUCT PANEL --}}
    <div class="product-panel">
        <div class="search-bar">
            <div class="search-wrap">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="text" class="search-input" id="searchInput" placeholder="Cari produk atau scan barcode...">
            </div>
        </div>
        <div class="product-grid" id="productGrid"></div>
    </div>

    {{-- CART PANEL --}}
    <div class="cart-panel">
        <div class="cart-header">
            <span class="cart-title">Keranjang <span class="cart-count" id="cartCount">0</span></span>
            <button class="btn-clear" onclick="clearCart()">Kosongkan</button>
        </div>
        <div class="cart-config">
            <select id="priceTier" class="config-select">
                <option value="eceran" selected>Harga Eceran</option>
                <option value="jual1">Harga Jual 1</option>
                <option value="jual2">Harga Jual 2</option>
                <option value="jual3">Harga Jual 3</option>
                <option value="minimal">Harga Minimal</option>
            </select>
            <select id="customerId" class="config-select">
                <option value="">-- Pelanggan Umum --</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone ?? '-' }})</option>
                @endforeach
            </select>
            <div id="customerInfo" class="customer-info">
                <div class="ci-row"><span class="ci-label">Batas Kredit:</span><span id="cLimit" class="ci-value">Rp 0</span></div>
                <div class="ci-row"><span class="ci-label">Hutang Berjalan:</span><span id="cDebt" class="ci-value">Rp 0</span></div>
                <div class="ci-row ci-divider"><span class="ci-label">Sisa Kredit:</span><span id="cRem" class="ci-value">Rp 0</span></div>
            </div>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="cart-empty"><div style="font-size:2rem;margin-bottom:0.5rem;opacity:0.4">🛒</div><div>Keranjang kosong</div></div>
        </div>
        <div class="cart-footer">
            <div class="summary-row"><span>Total item</span><span id="itemCount">0</span></div>
            <div class="summary-total"><span>Total</span><span id="totalDisplay">Rp 0</span></div>
            <button class="btn-pay" id="btnPay" onclick="openPay()" disabled>Proses Pembayaran</button>
        </div>
    </div>
</div>

{{-- PAYMENT MODAL --}}
<div class="modal-overlay" id="payModal">
    <div class="modal">
        <div class="modal-title">Pembayaran Eceran</div>
        <div class="modal-total-box">
            <div class="modal-total-label">Total Tagihan</div>
            <div class="modal-total-amount" id="modalTotal">Rp 0</div>
        </div>
        <div class="pay-methods">
            <div class="pay-method active" data-m="cash" onclick="setM('cash')">Tunai</div>
            <div class="pay-method" data-m="transfer" onclick="setM('transfer')">Transfer</div>
            <div class="pay-method" data-m="qris" onclick="setM('qris')">QRIS</div>
            <div class="pay-method" data-m="kredit" onclick="setM('kredit')">Kredit</div>
        </div>
        <div id="cashSec">
            <label class="form-label" id="lblPaid">Uang Diterima</label>
            <input type="number" class="form-input" id="paidInput" placeholder="0">
            <div class="quick-cash" id="quickCash"></div>
        </div>
        <div id="transferSec" style="display:none">
            <div class="bank-info">
                <div class="bank-info-title">Rekening Toko</div>
                <div class="bank-info-detail" id="bankDisplay"></div>
                <div class="bank-info-help" id="bankHelp"></div>
            </div>
            <label class="form-label">ID Transaksi Transfer <span style="color:var(--red)">*</span></label>
            <input type="text" class="form-input" id="transferRefInput" placeholder="No. referensi bank">
        </div>
        <div id="qrisSec" style="display:none">
            <label class="form-label">ID Transaksi QRIS <span style="color:var(--red)">*</span></label>
            <input type="text" class="form-input" id="qrisRefInput" placeholder="No. referensi QRIS">
        </div>
        <div class="change-box" id="changeBox" style="display:none">
            <div class="change-label">Kembalian</div>
            <div class="change-amount" id="changeDisplay">Rp 0</div>
        </div>
        <div class="debt-box" id="debtBox" style="display:none">
            <div class="debt-label">Sisa Hutang</div>
            <div class="debt-amount" id="debtDisplay">Rp 0</div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closePay()">Batal</button>
            <button class="btn-confirm" id="btnConfirm" onclick="doPayment()">Konfirmasi</button>
        </div>
    </div>
</div>

{{-- SUCCESS OVERLAY --}}
<div class="modal-overlay" id="successOverlay">
    <div class="success-box">
        <div class="success-icon">✓</div>
        <div class="success-title">Transaksi Selesai!</div>
        <div class="success-change-label" id="successLabel">Kembalian</div>
        <div class="success-change-amount" id="successChange">Rp 0</div>
        <button class="btn-success btn-print" id="btnCetakReceipt" onclick="printReceipt()">Cetak Struk</button>
        <button class="btn-success btn-new" onclick="newTrx()">+ Transaksi Baru</button>
    </div>
</div>

@php
    $storeSettingData = [
        'bank_name' => $storeSetting->bank_name ?? null,
        'bank_account_number' => $storeSetting->bank_account_number ?? null,
        'bank_account_holder' => $storeSetting->bank_account_holder ?? null,
    ];
@endphp
<template id="productsJson">@json($products)</template>
<template id="customersJson">@json($customers)</template>
<template id="storeSettingJson">@json($storeSettingData)</template>

<script>
let lastTransactionId = null;
function printReceipt() { if(lastTransactionId) window.open('/print/receipt/' + lastTransactionId, '_blank'); }

let PRODUCTS = JSON.parse(document.getElementById('productsJson')?.innerHTML || '[]');
let CUSTOMERS = JSON.parse(document.getElementById('customersJson')?.innerHTML || '[]');
const STORE_SETTING = JSON.parse(document.getElementById('storeSettingJson')?.innerHTML || '{}');
let cart = [], method = 'cash';
let priceTier = 'eceran';
setInterval(()=>{ document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID'); },1000);

// Payment input event listeners
document.getElementById('paidInput').addEventListener('input', function(){ if(method==='kredit') calcDebt(); else calcChange(); });
document.addEventListener('keydown', function(e){
    if(e.key === 'Escape') { if(document.getElementById('successOverlay').classList.contains('show')) newTrx(); else closePay(); }
    if(e.key === 'Enter' && document.getElementById('payModal').classList.contains('show')) doPayment();
});

let productSearchTimeout = null;
const productSearchInput = document.querySelector('.search-input');
productSearchInput.addEventListener('input', function(e) {
    clearTimeout(productSearchTimeout);
    productSearchTimeout = setTimeout(() => { fetchProducts(e.target.value); }, 400);
});

function fetchProducts(query = '') {
    const url = '{{ route("kasir.eceran.search_products") }}' + '?_t=' + Date.now() + (query ? '&q=' + encodeURIComponent(query) : '');
    fetch(url).then(res => { if(!res.ok) throw new Error('HTTP '+res.status); return res.json(); })
        .then(data => { PRODUCTS = data; render(); })
        .catch(err => { console.error('Fetch error:', err); document.getElementById('productGrid').innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:2rem;color:var(--red);font-weight:700;">Gagal memuat produk.</div>'; });
}

function resolveUnitPrice(u){
    if(!u || !u.prices) return 0;
    const rawPrice = u.prices[priceTier] || u.prices.eceran || 0;
    const priceVal = parseFloat(String(rawPrice).replace(/\./g, '').replace(/,/g, '.')) || 0;
    return priceVal;
}

function refreshCartPrices(){
    cart.forEach(item => { const u = item.units.find(x => x.id == item.unitId); if(u) item.price = resolveUnitPrice(u); });
    render(); renderCart();
}

document.getElementById('priceTier')?.addEventListener('change', function(){ priceTier = this.value || 'eceran'; refreshCartPrices(); });

document.getElementById('customerId').addEventListener('change', function() {
    const cid = this.value, info = document.getElementById('customerInfo');
    if(!cid) { info.style.display = 'none'; return; }
    const c = CUSTOMERS.find(x => x.id == cid);
    if(c) {
        const lim = parseFloat(c.credit_limit) || 0, deb = parseFloat(c.current_debt) || 0, rem = Math.max(0, lim - deb);
        document.getElementById('cLimit').textContent = 'Rp ' + fmt(lim);
        document.getElementById('cDebt').textContent = 'Rp ' + fmt(deb);
        document.getElementById('cRem').textContent = 'Rp ' + fmt(rem);
        info.style.display = 'block';
    } else { info.style.display = 'none'; }
});

function render(){
    const q = document.getElementById('searchInput').value.toLowerCase();
    const list = PRODUCTS.filter(p => (!q || p.name.toLowerCase().includes(q) || (p.sku && p.sku.toLowerCase().includes(q))));
    const grid = document.getElementById('productGrid');
    if(!list.length){ grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:3rem;color:#475569">Tidak ada produk</div>'; return; }
    grid.innerHTML = list.map(p => {
        const unitRows = p.units && p.units.length
            ? p.units.map(u => `<div class="prod-unit-row"><span class="prod-unit-name">${u.name}</span><span class="prod-unit-price">Rp ${fmt(resolveUnitPrice(u))}</span></div>`).join('')
            : `<div class="prod-unit-row"><span style="color:var(--muted)">pcs</span><span class="prod-unit-price">Rp ${fmt((p.prices&&p.prices.eceran)||0)}</span></div>`;
        const breakdownStr = p.stock_breakdown && p.stock_breakdown.length > 0
            ? p.stock_breakdown.map(b => b.warehouse + ': ' + b.qty).join(', ') : '';
        const breakdownHtml = breakdownStr
            ? `<div style="font-size:0.6rem;color:#0284c7;margin-top:2px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" title="${breakdownStr}">${breakdownStr}</div>` : '';
        const stockClass = p.stock <= 0 ? '' : (p.stock <= 5 ? ' low' : '');
        return `<div class="product-card ${p.stock<=0?'prod-nostock':''}" onclick="${p.stock>0?`addToCart(${p.id})`:''}">
            <span class="prod-stock${stockClass}">${p.stock}</span>
            <div class="prod-name">${p.name}</div>
            <div class="prod-cat">${p.category}${breakdownHtml}</div>
            <div class="prod-units-list">${unitRows}</div>
        </div>`;
    }).join('');
}
render();

function addToCart(id){
    id = Number(id);
    const p = PRODUCTS.find(x => Number(x.id) === id);
    if(!p || p.stock <= 0) return;
    const units = (p.units && p.units.length > 0) ? p.units : [];
    const u = units[0] || {id: null, name: 'pcs', factor: 1, prices: p.prices || {eceran: 0}};
    const unitPrice = resolveUnitPrice(u);
    const factor = u.factor || 1;
    let wh = null;
    if(p.stock_breakdown && p.stock_breakdown.length > 0) {
        wh = p.stock_breakdown.find(b => b.qty >= factor);
        if(!wh) wh = p.stock_breakdown[0];
    } else { wh = {warehouse_id: null, qty: 1}; }
    const key = `${id}_${u.id||'x'}_${wh.warehouse_id}`;
    const ex = cart.find(c => c.key === key);
    if(ex) {
        const newQty = ex.qty + 1;
        const whCheck = (ex.breakdowns||[]).find(b => b.warehouse_id == ex.warehouse_id);
        if(whCheck && newQty * ex.factor > whCheck.qty) return alert(`Stok di gudang ini (${whCheck.qty} base unit) tidak cukup! Sudah ada ${ex.qty} ${ex.unitName} di keranjang.`);
        ex.qty = newQty;
    } else {
        if(1 * factor > wh.qty) return alert(`Stok di gudang ini (${wh.qty} base unit) tidak cukup untuk satuan ${u.name} (butuh ${factor} base unit)!`);
        cart.push({key, id, name:p.name, units:units, unitId:u.id, unitName:u.name, factor:factor, price:unitPrice, qty:1, warehouse_id:wh.warehouse_id, breakdowns:p.stock_breakdown||[]});
    }
    renderCart();
}

function changeWarehouse(i, wh_id){
    const item = cart[i], newWh = (item.breakdowns||[]).find(b => b.warehouse_id == wh_id);
    if(newWh) {
        if(item.qty * item.factor > newWh.qty) {
            alert(`Stok di gudang yang dipilih (${newWh.qty} base unit) tidak mencukupi!`);
            item.qty = Math.floor(newWh.qty / item.factor);
            if(item.qty === 0) { alert(`Item ${item.name} dihapus karena stok tidak mencukupi.`); removeItem(i); return; }
        }
        item.warehouse_id = newWh.warehouse_id;
        item.key = `${item.id}_${item.unitId}_${newWh.warehouse_id}`;
    }
    renderCart();
}

function changeUnit(i, uid){
    const item = cart[i], u = item.units.find(x => x.id == uid);
    if(u) {
        const wh = (item.breakdowns||[]).find(b => b.warehouse_id == item.warehouse_id);
        if(wh && (item.qty * u.factor > wh.qty)) { alert(`Stok tidak cukup untuk satuan ${u.name}!`); }
        else { item.unitId=u.id; item.unitName=u.name; item.factor=u.factor||1; item.price=resolveUnitPrice(u); item.key=`${item.id}_${u.id}_${item.warehouse_id}`; }
    }
    renderCart();
}

function removeItem(i){ cart.splice(i,1); renderCart(); }

function changeQty(i, d){
    const item = cart[i], newQty = Math.max(1, item.qty + d);
    if(d > 0) {
        const wh = (item.breakdowns||[]).find(b => b.warehouse_id == item.warehouse_id);
        if(wh && (newQty * item.factor > wh.qty)) { alert(`Stok tidak cukup!`); return; }
    }
    item.qty = newQty; renderCart();
}

function clearCart(){ cart = []; renderCart(); }

function renderCart(){
    const el = document.getElementById('cartItems');
    if(!cart.length) {
        el.innerHTML = '<div class="cart-empty"><div style="font-size:2rem;margin-bottom:0.5rem;opacity:0.4">🛒</div><div>Keranjang kosong</div></div>';
        document.getElementById('cartCount').textContent = 0;
        document.getElementById('itemCount').textContent = '0';
        document.getElementById('totalDisplay').textContent = 'Rp 0';
        document.getElementById('btnPay').disabled = true;
        return;
    }
    const total = cart.reduce((s,c) => s + c.price * c.qty, 0);
    el.innerHTML = cart.map((c, i) => `
        <div class="cart-item">
            <div class="ci-top"><div class="ci-name">${c.name}</div><button class="ci-remove" onclick="removeItem(${i})">✕</button></div>
            <select class="ci-warehouse" onchange="changeWarehouse(${i}, this.value)">
                ${(c.breakdowns || []).map(b => `<option value="${b.warehouse_id}" ${b.warehouse_id == c.warehouse_id ? 'selected' : ''}>${b.warehouse} (${b.qty})</option>`).join('')}
            </select>
            <div class="ci-bottom">
                ${(c.units||[]).length > 0 ? `<select class="unit-select" onchange="changeUnit(${i}, this.value)">${(c.units||[]).map(u=>`<option value="${u.id}" ${u.id==c.unitId?'selected':''}>${u.name} — Rp ${fmt(resolveUnitPrice(u))}</option>`).join('')}</select>` : `<span class="unit-select" style="background:#f8fafc;border:1px solid var(--border);border-radius:6px;padding:.35rem .5rem;font-size:.68rem;color:#475569;">${c.unitName||'pcs'} — Rp ${fmt(c.price)}</span>`}
                <div class="qty-ctrl">
                    <button class="qty-btn" onclick="changeQty(${i},-1)">−</button>
                    <span class="qty-num">${c.qty}</span>
                    <button class="qty-btn" onclick="changeQty(${i},1)">+</button>
                </div>
                <span class="ci-subtotal">Rp ${fmt(c.price * c.qty)}</span>
            </div>
        </div>
    `).join('');
    document.getElementById('cartCount').textContent = cart.length;
    document.getElementById('itemCount').textContent = cart.reduce((s,c) => s + c.qty, 0) + ' item';
    document.getElementById('totalDisplay').textContent = 'Rp ' + fmt(total);
    document.getElementById('btnPay').disabled = false;
}

function getTotal(){ return cart.reduce((s,c) => s + c.price * c.qty, 0); }

function openPay(){
    const total = getTotal();
    document.getElementById('modalTotal').textContent = 'Rp ' + fmt(total);
    document.getElementById('paidInput').value = '';
    document.getElementById('changeBox').style.display = 'none';
    document.getElementById('debtBox').style.display = 'none';
    const tr = document.getElementById('transferRefInput'); if(tr) tr.value = '';
    const qr = document.getElementById('qrisRefInput'); if(qr) qr.value = '';
    const steps = [...new Set([total, Math.ceil(total/5000)*5000, Math.ceil(total/10000)*10000, Math.ceil(total/50000)*50000, Math.ceil(total/100000)*100000])].filter(v => v >= total).slice(0,4);
    document.getElementById('quickCash').innerHTML = `<button class="quick-btn pas" onclick="setPaid(${total})">Uang Pas</button>` + steps.map(v => `<button class="quick-btn" onclick="setPaid(${v})">Rp ${fmt(v)}</button>`).join('');
    document.getElementById('payModal').classList.add('show');
    setM(method);
    document.getElementById('paidInput').focus();
}

function closePay(){ document.getElementById('payModal').classList.remove('show'); }
function setPaid(v){ document.getElementById('paidInput').value = v; if(method==='kredit') calcDebt(); else calcChange(); }

function renderBankInfo(){
    const bank = (STORE_SETTING?.bank_name || '').trim(), acc = (STORE_SETTING?.bank_account_number || '').trim(), holder = (STORE_SETTING?.bank_account_holder || '').trim();
    const display = document.getElementById('bankDisplay'), help = document.getElementById('bankHelp');
    if(!display || !help) return;
    if(!bank && !acc && !holder) { display.textContent = 'Belum diatur'; help.textContent = 'Isi nomor rekening di Pengaturan Toko.'; return; }
    const parts = []; if(bank) parts.push(bank); if(acc) parts.push(acc); if(holder) parts.push('a/n ' + holder);
    display.textContent = parts.join(' • '); help.textContent = 'Pastikan ID transaksi sesuai bukti transfer.';
}

function setM(m){
    method = m;
    document.querySelectorAll('.pay-method').forEach(x => x.classList.toggle('active', x.dataset.m === m));
    document.getElementById('cashSec').style.display = (m==='cash' || m==='kredit') ? 'block' : 'none';
    document.getElementById('transferSec').style.display = (m==='transfer') ? 'block' : 'none';
    document.getElementById('qrisSec').style.display = (m==='qris') ? 'block' : 'none';
    document.getElementById('lblPaid').textContent = m==='kredit' ? 'Uang Muka (DP) — Opsional' : 'Uang Diterima';
    document.getElementById('quickCash').style.display = m==='kredit' ? 'none' : 'flex';
    document.getElementById('changeBox').style.display = 'none';
    document.getElementById('debtBox').style.display = 'none';
    if(m === 'cash') calcChange();
    else if(m === 'kredit') calcDebt();
    if(m === 'transfer') renderBankInfo();
}

function calcChange(){
    const total = getTotal(), paid = parseFloat(document.getElementById('paidInput').value) || 0;
    const box = document.getElementById('changeBox');
    if(paid > 0 && paid >= total) {
        document.getElementById('changeDisplay').textContent = 'Rp ' + fmt(paid - total);
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}

function calcDebt(){
    const total = getTotal(), dp = parseFloat(document.getElementById('paidInput').value) || 0;
    const box = document.getElementById('debtBox');
    if(dp >= total) {
        box.style.display = 'none';
    } else {
        document.getElementById('debtDisplay').textContent = 'Rp ' + fmt(total - dp);
        box.style.display = 'block';
    }
}

function doPayment(){
    const total = getTotal();
    let paid = total;
    if(method === 'cash') paid = parseFloat(document.getElementById('paidInput').value) || 0;
    else if(method === 'kredit') paid = parseFloat(document.getElementById('paidInput').value) || 0;
    if(method === 'cash' && paid < total) { alert('Uang tunai yang diterima kurang!'); return; }
    let paymentRef = null;
    if(method === 'transfer') { paymentRef = (document.getElementById('transferRefInput')?.value || '').trim(); if(!paymentRef) { alert('ID transaksi transfer wajib diisi.'); return; } }
    if(method === 'qris') { paymentRef = (document.getElementById('qrisRefInput')?.value || '').trim(); if(!paymentRef) { alert('ID transaksi QRIS wajib diisi.'); return; } }
    const custId = document.getElementById('customerId').value;
    if(method === 'kredit') { if(!custId) { alert('Pilih Pelanggan untuk pembayaran Kredit!'); return; } if(paid > total) { alert('DP Kredit tidak boleh melebihi total tagihan!'); return; } }
    const change = method === 'cash' ? Math.max(0, paid - total) : 0;
    const payload = {
        price_tier: priceTier,
        items: cart.map(c => ({ product_id: c.id, unit_qty: c.qty, unit_conversion_id: c.unitId, warehouse_id: c.warehouse_id })),
        total_amount: total, paid_amount: paid, payment_method: method, payment_reference: paymentRef,
        customer_id: custId ? custId : null
    };
    const btn = document.getElementById('btnConfirm'); btn.disabled = true; btn.textContent = 'Memproses...';
    fetch('{{ route("kasir.eceran.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload)
    }).then(r => r.json()).then(d => {
        if(d.success) {
            lastTransactionId = d.transaction_id;
            closePay();
            const label = document.getElementById('successLabel');
            const amtEl = document.getElementById('successChange');
            if(method === 'kredit') {
                label.textContent = 'Sisa Hutang';
                const debt = Math.max(0, total - paid);
                amtEl.textContent = debt > 0 ? 'Rp ' + fmt(debt) : 'Lunas';
                amtEl.style.color = debt > 0 ? 'var(--red)' : 'var(--green)';
            } else {
                label.textContent = 'Kembalian';
                amtEl.textContent = 'Rp ' + fmt(change);
                amtEl.style.color = 'var(--amber)';
            }
            document.getElementById('successOverlay').classList.add('show');
        } else alert('Gagal: ' + d.message);
    }).catch(() => alert('Gagal menghubungi server.')).finally(() => { btn.disabled = false; btn.textContent = 'Konfirmasi'; });
}

function newTrx(){ cart=[]; method='cash'; document.getElementById('priceTier').value='eceran'; priceTier='eceran'; document.getElementById('customerId').value=''; document.getElementById('customerInfo').style.display='none'; renderCart(); document.getElementById('successOverlay').classList.remove('show'); fetchProducts(document.querySelector('.search-input').value); }
function fmt(n){ return Math.round(n).toLocaleString('id-ID'); }
</script>
</body>
</html>
