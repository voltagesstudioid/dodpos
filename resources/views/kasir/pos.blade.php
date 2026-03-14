<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir / POS — DODPOS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Inter',sans-serif; background:#0f172a; color:#e2e8f0; height:100vh; display:flex; flex-direction:column; overflow:hidden; }

        /* TOP BAR */
        .topbar { background:#1e293b; border-bottom:1px solid #334155; padding:0.6rem 1.25rem; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
        .pos-badge { background:linear-gradient(135deg,#6366f1,#4f46e5); padding:0.3rem 0.875rem; border-radius:999px; font-size:0.75rem; font-weight:800; letter-spacing:0.04em; color:#fff; }
        .topbar-title { font-size:1rem; font-weight:700; color:#f1f5f9; }
        .btn-back { background:#334155; border:none; color:#94a3b8; padding:0.4rem 0.875rem; border-radius:8px; cursor:pointer; font-size:0.8rem; text-decoration:none; }
        .btn-back:hover { background:#475569; color:#e2e8f0; }

        /* LAYOUT */
        .pos-layout { display:grid; grid-template-columns:1fr 360px; flex:1; overflow:hidden; }

        /* LEFT */
        .product-panel { display:flex; flex-direction:column; overflow:hidden; }
        .search-bar { padding:0.875rem 1.25rem; background:#1e293b; border-bottom:1px solid #334155; display:flex; gap:0.75rem; }
        .search-input { flex:1; background:#0f172a; border:1px solid #334155; border-radius:10px; padding:0.6rem 1rem; color:#e2e8f0; font-size:0.875rem; outline:none; }
        .search-input:focus { border-color:#6366f1; }
        .search-input::placeholder { color:#475569; }
        .category-bar { padding:0.625rem 1.25rem; background:#1e293b; border-bottom:1px solid #334155; display:flex; gap:0.5rem; overflow-x:auto; flex-shrink:0; }
        .category-bar::-webkit-scrollbar { height:4px; }
        .cat-btn { background:#283548; border:none; color:#94a3b8; padding:0.3rem 0.875rem; border-radius:999px; font-size:0.75rem; cursor:pointer; white-space:nowrap; font-family:inherit; transition:all 0.15s; }
        .cat-btn.active, .cat-btn:hover { background:#6366f1; color:#fff; }

        .product-grid { flex:1; overflow-y:auto; padding:1rem; display:grid; grid-template-columns:repeat(auto-fill, minmax(148px, 1fr)); gap:0.75rem; align-content:start; }
        .product-grid::-webkit-scrollbar { width:6px; }
        .product-grid::-webkit-scrollbar-thumb { background:#334155; border-radius:3px; }

        .product-card { background:#1e293b; border:1px solid #334155; border-radius:12px; padding:0.875rem 0.75rem; cursor:pointer; transition:all 0.15s; user-select:none; position:relative; }
        .product-card:hover { background:#253348; border-color:#6366f1; transform:translateY(-1px); box-shadow:0 4px 16px rgba(99,102,241,0.2); }
        .product-card:active { transform:scale(0.97); }
        .prod-name { font-size:0.8rem; font-weight:600; color:#e2e8f0; margin-bottom:0.25rem; line-height:1.3; }
        .prod-cat  { font-size:0.65rem; color:#64748b; margin-bottom:0.5rem; }
        .prod-units { display:flex; flex-wrap:wrap; gap:0.25rem; margin-bottom:0.375rem; }
        .unit-tag { background:#283548; color:#94a3b8; font-size:0.6rem; padding:0.1rem 0.4rem; border-radius:999px; }
        .prod-price { font-size:0.875rem; font-weight:800; color:#a5b4fc; }
        .prod-stock-badge { position:absolute; top:0.5rem; right:0.5rem; font-size:0.6rem; background:#0f172a; color:#64748b; padding:0.1rem 0.4rem; border-radius:999px; }
        .prod-nostock { opacity:0.35; cursor:not-allowed; }
        .prod-nostock:hover { transform:none; border-color:#334155; box-shadow:none; }

        /* RIGHT: Cart */
        .cart-panel { background:#1e293b; border-left:1px solid #334155; display:flex; flex-direction:column; overflow:hidden; }
        .cart-header { padding:0.875rem 1.25rem; border-bottom:1px solid #334155; display:flex; justify-content:space-between; align-items:center; }
        .cart-title { font-size:0.875rem; font-weight:700; color:#f1f5f9; }
        .cart-count { background:#6366f1; color:#fff; border-radius:999px; padding:0.1rem 0.5rem; font-size:0.7rem; font-weight:700; }
        .btn-clear { background:#ef4444; border:none; color:#fff; padding:0.2rem 0.6rem; border-radius:6px; font-size:0.7rem; cursor:pointer; }

        .cart-items { flex:1; overflow-y:auto; padding:0.75rem; }
        .cart-items::-webkit-scrollbar { width:5px; }
        .cart-items::-webkit-scrollbar-thumb { background:#334155; border-radius:3px; }
        .cart-empty { text-align:center; padding:3rem 1rem; color:#475569; }
        .cart-empty-icon { font-size:2.5rem; margin-bottom:0.5rem; }

        .cart-item { background:#0f172a; border-radius:10px; padding:0.75rem; margin-bottom:0.5rem; border:1px solid #1e2d40; }
        .ci-top { display:flex; justify-content:space-between; align-items:start; margin-bottom:0.5rem; }
        .ci-name { font-size:0.8rem; font-weight:600; color:#e2e8f0; flex:1; line-height:1.3; }
        .ci-remove { background:none; border:none; color:#ef4444; cursor:pointer; font-size:0.9rem; padding:0; flex-shrink:0; }
        .ci-bottom { display:flex; align-items:center; gap:0.5rem; }
        .unit-select { flex:1; background:#1e293b; border:1px solid #334155; color:#e2e8f0; border-radius:6px; padding:0.2rem 0.4rem; font-size:0.7rem; cursor:pointer; font-family:inherit; min-width:0; }
        .qty-ctrl { display:flex; align-items:center; gap:0.3rem; flex-shrink:0; }
        .qty-btn { width:22px; height:22px; background:#334155; border:none; color:#e2e8f0; border-radius:5px; cursor:pointer; font-size:0.9rem; display:flex; align-items:center; justify-content:center; }
        .qty-btn:hover { background:#6366f1; }
        .qty-num { min-width:24px; text-align:center; font-weight:700; font-size:0.8rem; }
        .ci-subtotal { font-size:0.78rem; font-weight:700; color:#a5b4fc; white-space:nowrap; }

        /* Cart Footer */
        .cart-footer { border-top:1px solid #334155; padding:1rem 1.25rem; flex-shrink:0; }
        .summary-row { display:flex; justify-content:space-between; font-size:0.75rem; color:#64748b; margin-bottom:0.3rem; }
        .summary-total { display:flex; justify-content:space-between; font-size:1.1rem; font-weight:800; color:#f1f5f9; margin:0.625rem 0; }
        .btn-pay { width:100%; background:linear-gradient(135deg,#6366f1,#4f46e5); border:none; color:#fff; padding:0.875rem; border-radius:12px; font-size:1rem; font-weight:700; cursor:pointer; font-family:inherit; transition:all 0.2s; }
        .btn-pay:hover { transform:translateY(-1px); box-shadow:0 8px 20px rgba(99,102,241,0.4); }
        .btn-pay:disabled { opacity:0.35; cursor:not-allowed; transform:none; box-shadow:none; }

        /* Payment Modal */
        .modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:1000; display:none; align-items:center; justify-content:center; }
        .modal-overlay.show { display:flex; }
        .modal { background:#1e293b; border-radius:16px; padding:2rem; width:90%; max-width:420px; border:1px solid #334155; }
        .modal-title { font-size:1.1rem; font-weight:700; margin-bottom:1.25rem; }
        .modal-total-box { background:#0f172a; border-radius:10px; padding:1rem; text-align:center; margin-bottom:1.25rem; border:1px solid #334155; }
        .modal-total-label { font-size:0.7rem; color:#64748b; margin-bottom:0.25rem; }
        .modal-total-amount { font-size:2rem; font-weight:800; color:#a5b4fc; }
        .form-label { font-size:0.7rem; font-weight:600; color:#94a3b8; margin-bottom:0.375rem; display:block; }
        .form-input { width:100%; background:#0f172a; border:1px solid #334155; border-radius:8px; padding:0.625rem 0.875rem; color:#e2e8f0; font-size:1rem; font-family:inherit; outline:none; }
        .form-input:focus { border-color:#6366f1; }
        .pay-methods { display:grid; grid-template-columns:repeat(3,1fr); gap:0.5rem; margin-bottom:1rem; }
        .pay-method { background:#0f172a; border:2px solid #334155; border-radius:8px; padding:0.5rem; text-align:center; cursor:pointer; font-size:0.75rem; font-weight:600; transition:all 0.15s; }
        .pay-method.active { border-color:#6366f1; color:#a5b4fc; background:#1a1f4a; }
        .change-box { background:#0f172a; border-radius:10px; padding:0.875rem; text-align:center; margin:0.75rem 0; border:1px solid #334155; }
        .change-label { font-size:0.7rem; color:#64748b; }
        .change-amount { font-size:1.5rem; font-weight:800; color:#f59e0b; }
        .quick-cash { display:flex; flex-wrap:wrap; gap:0.4rem; margin-top:0.5rem; }
        .quick-btn { background:#334155; border:none; color:#e2e8f0; padding:0.25rem 0.625rem; border-radius:6px; cursor:pointer; font-size:0.72rem; font-family:inherit; }
        .quick-btn:hover { background:#6366f1; }
        .modal-actions { display:flex; gap:0.75rem; margin-top:1.25rem; }
        .btn-confirm { flex:2; background:linear-gradient(135deg,#6366f1,#4f46e5); border:none; color:#fff; padding:0.875rem; border-radius:10px; font-size:0.95rem; font-weight:700; cursor:pointer; font-family:inherit; }
        .btn-cancel  { flex:1; background:#334155; border:none; color:#94a3b8; padding:0.875rem; border-radius:10px; cursor:pointer; font-family:inherit; }

        /* Success */
        .success-box { background:#1e293b; border-radius:16px; padding:2rem; width:90%; max-width:340px; border:1px solid #334155; text-align:center; }
        .btn-new { background:#6366f1; border:none; color:#fff; padding:0.75rem 2rem; border-radius:10px; font-size:0.9rem; font-weight:700; cursor:pointer; font-family:inherit; margin-top:1rem; }
        .btn-new:hover { background:#4f46e5; }
    </style>
</head>
<body>

{{-- TOP BAR --}}
<div class="topbar">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <a href="{{ route('dashboard') }}" class="btn-back">← Kembali</a>
        <span class="pos-badge">POS</span>
        <span class="topbar-title">Kasir / Point of Sale</span>
    </div>
    <div style="display:flex;align-items:center;gap:1rem;">
        <span style="font-size:0.8rem;color:#64748b;" id="clock"></span>
        <span style="font-size:0.8rem;color:#64748b;">👤 {{ Auth::user()->name }}</span>
    </div>
</div>

{{-- POS LAYOUT --}}
<div class="pos-layout">

    {{-- LEFT: Products --}}
    <div class="product-panel">
        <div class="search-bar">
            <input type="text" class="search-input" id="searchInput" placeholder="🔍 Cari nama produk, SKU, atau scan barcode...">
        </div>
        <div class="category-bar" id="categoryBar">
            <button class="cat-btn active" data-cat="">Semua</button>
        </div>
        <div class="product-grid" id="productGrid"></div>
    </div>

    {{-- RIGHT: Cart --}}
    <div class="cart-panel">
        <div class="cart-header">
            <span class="cart-title">🛒 Keranjang &nbsp;<span class="cart-count" id="cartCount">0</span></span>
            <button class="btn-clear" onclick="clearCart()">🗑 Kosongkan</button>
        </div>
        <div class="cart-items" id="cartItems">
            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>
                <div style="font-weight:600;">Keranjang kosong</div>
                <div style="font-size:0.75rem;margin-top:0.25rem;color:#475569;">Klik produk untuk menambahkan</div>
            </div>
        </div>
        <div class="cart-footer">
            <div class="summary-row"><span>Total item</span><span id="itemCount">0 item</span></div>
            <div class="summary-total"><span>Total</span><span id="totalDisplay">Rp 0</span></div>
            <button class="btn-pay" id="btnPay" onclick="openPay()" disabled>💳 Proses Pembayaran</button>
        </div>
    </div>
</div>

{{-- Payment Modal --}}
<div class="modal-overlay" id="payModal">
    <div class="modal">
        <div class="modal-title">💳 Pembayaran</div>
        <div class="modal-total-box">
            <div class="modal-total-label">Total Tagihan</div>
            <div class="modal-total-amount" id="modalTotal">Rp 0</div>
        </div>
        <div>
            <div class="form-label">Metode Pembayaran</div>
            <div class="pay-methods">
                <div class="pay-method active" data-m="cash"     onclick="setMethod('cash')">💵 Tunai</div>
                <div class="pay-method"         data-m="transfer" onclick="setMethod('transfer')">🏦 Transfer</div>
                <div class="pay-method"         data-m="qris"     onclick="setMethod('qris')">📱 QRIS</div>
            </div>
        </div>
        <div id="cashSection">
            <label class="form-label">Uang Diterima</label>
            <input type="number" class="form-input" id="paidInput" placeholder="0" oninput="calcChange()">
            <div class="quick-cash" id="quickCash"></div>
        </div>
        <div class="change-box" id="changeBox" style="display:none;">
            <div class="change-label">Kembalian</div>
            <div class="change-amount" id="changeDisplay">Rp 0</div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closePay()">Batal</button>
            <button class="btn-confirm" id="btnConfirm" onclick="doPayment()">✅ Konfirmasi</button>
        </div>
    </div>
</div>

{{-- Success --}}
<div class="modal-overlay" id="successOverlay">
    <div class="success-box">
        <div style="font-size:4rem;margin-bottom:0.75rem;">✅</div>
        <div style="font-size:1.25rem;font-weight:800;color:#a5b4fc;margin-bottom:0.25rem;">Transaksi Selesai!</div>
        <div style="font-size:0.8rem;color:#64748b;">Kembalian</div>
        <div style="font-size:2rem;font-weight:800;color:#f59e0b;margin:0.5rem 0;" id="successChange">Rp 0</div>
        <button class="btn-new" onclick="newTrx()">+ Transaksi Baru</button>
    </div>
</div>

<script>
const PRODUCTS = @json($products);
let cart = [], method = 'cash';
const cats = [...new Set(PRODUCTS.map(p => p.category).filter(Boolean))].sort();

// Clock
setInterval(() => { document.getElementById('clock').textContent = new Date().toLocaleTimeString('id-ID'); }, 1000);

// Build categories
const catBar = document.getElementById('categoryBar');
cats.forEach(c => {
    const b = document.createElement('button');
    b.className = 'cat-btn'; b.dataset.cat = c; b.textContent = c;
    b.onclick = () => { document.querySelectorAll('.cat-btn').forEach(x => x.classList.remove('active')); b.classList.add('active'); render(); };
    catBar.appendChild(b);
});

function render() {
    const q   = document.getElementById('searchInput').value.toLowerCase();
    const cat = document.querySelector('.cat-btn.active')?.dataset.cat || '';
    const list = PRODUCTS.filter(p =>
        (!cat || p.category === cat) &&
        (!q || p.name.toLowerCase().includes(q) || (p.sku && p.sku.toLowerCase().includes(q)))
    );
    const grid = document.getElementById('productGrid');
    if (!list.length) { grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:3rem;color:#475569;">Tidak ada produk.</div>'; return; }

    grid.innerHTML = list.map(p => {
        const basePrice = p.units.length ? p.units[0].price : p.price;
        const unitTags  = p.units.slice(0, 3).map(u => `<span class="unit-tag">${u.name}</span>`).join('');
        const noStock   = p.stock <= 0;
        return `
            <div class="product-card ${noStock ? 'prod-nostock' : ''}" onclick="${noStock ? '' : `addToCart(${p.id})`}">
                <span class="prod-stock-badge">📦 ${p.stock}</span>
                <div class="prod-name">${p.name}</div>
                <div class="prod-cat">${p.category}</div>
                <div class="prod-units">${unitTags}</div>
                <div class="prod-price">Rp ${fmt(basePrice)}</div>
            </div>
        `;
    }).join('');
}
document.getElementById('searchInput').addEventListener('input', render);
render();

// Cart
function addToCart(id) {
    const p = PRODUCTS.find(x => x.id === id);
    if (!p || p.stock <= 0) return;
    const u    = p.units[0] || { id: 0, name: '-', factor: 1, price: p.price };
    const key  = `${id}_${u.id}`;
    const ex   = cart.find(c => c.key === key);
    if (ex) ex.qty++;
    else cart.push({ key, id, name: p.name, units: p.units, unitId: u.id, unitName: u.name, factor: u.factor, price: u.price, qty: 1 });
    renderCart();
}

function changeUnit(idx, uid) {
    const item = cart[idx];
    const u = item.units.find(x => x.id == uid);
    if (u) { item.unitId = u.id; item.unitName = u.name; item.factor = u.factor; item.price = u.price; item.key = `${item.id}_${u.id}`; }
    renderCart();
}

function changeQty(idx, d) { cart[idx].qty = Math.max(1, cart[idx].qty + d); renderCart(); }
function removeItem(idx)  { cart.splice(idx, 1); renderCart(); }
function clearCart()      { cart = []; renderCart(); }

function renderCart() {
    const el = document.getElementById('cartItems');
    if (!cart.length) {
        el.innerHTML = '<div class="cart-empty"><div class="cart-empty-icon">🛒</div><div style="font-weight:600;">Keranjang kosong</div><div style="font-size:0.75rem;margin-top:0.25rem;color:#475569;">Klik produk untuk menambahkan</div></div>';
        document.getElementById('cartCount').textContent = 0;
        document.getElementById('itemCount').textContent = '0 item';
        document.getElementById('totalDisplay').textContent = 'Rp 0';
        document.getElementById('btnPay').disabled = true;
        return;
    }
    const total = cart.reduce((s, c) => s + c.price * c.qty, 0);
    el.innerHTML = cart.map((c, i) => `
        <div class="cart-item">
            <div class="ci-top">
                <div class="ci-name">${c.name}</div>
                <button class="ci-remove" onclick="removeItem(${i})">✕</button>
            </div>
            <div class="ci-bottom">
                <select class="unit-select" onchange="changeUnit(${i}, this.value)">
                    ${c.units.map(u => `<option value="${u.id}" ${u.id == c.unitId ? 'selected' : ''}>${u.name} — Rp ${fmt(u.price)}</option>`).join('')}
                </select>
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
    document.getElementById('itemCount').textContent = cart.reduce((s, c) => s + c.qty, 0) + ' item';
    document.getElementById('totalDisplay').textContent = 'Rp ' + fmt(total);
    document.getElementById('btnPay').disabled = false;
}

// Payment
function openPay() {
    const total = getTotal();
    document.getElementById('modalTotal').textContent = 'Rp ' + fmt(total);
    document.getElementById('paidInput').value = '';
    document.getElementById('changeBox').style.display = 'none';
    const steps = [...new Set([total, Math.ceil(total/5000)*5000, Math.ceil(total/10000)*10000, Math.ceil(total/50000)*50000, Math.ceil(total/100000)*100000])].slice(0, 4);
    document.getElementById('quickCash').innerHTML = steps.map(v => `<button class="quick-btn" onclick="setPaid(${v})">Rp ${fmt(v)}</button>`).join('');
    document.getElementById('payModal').classList.add('show');
}
function closePay() { document.getElementById('payModal').classList.remove('show'); }
function getTotal() { return cart.reduce((s, c) => s + c.price * c.qty, 0); }
function setPaid(v) { document.getElementById('paidInput').value = v; calcChange(); }
function setMethod(m) {
    method = m;
    document.querySelectorAll('.pay-method').forEach(x => x.classList.remove('active'));
    document.querySelector(`.pay-method[data-m="${m}"]`).classList.add('active');
    document.getElementById('cashSection').style.display = m === 'cash' ? 'block' : 'none';
    if (m !== 'cash') document.getElementById('changeBox').style.display = 'none';
}
function calcChange() {
    const total = getTotal(), paid = parseFloat(document.getElementById('paidInput').value) || 0;
    const change = paid - total;
    document.getElementById('changeDisplay').textContent = 'Rp ' + fmt(Math.max(0, change));
    document.getElementById('changeBox').style.display = 'block';
}

function doPayment() {
    const total = getTotal();
    const paid  = method === 'cash' ? (parseFloat(document.getElementById('paidInput').value) || 0) : total;
    if (method === 'cash' && paid < total) { alert('Uang yang diterima kurang!'); return; }
    const change = Math.max(0, paid - total);

    const payload = {
        items: cart.map(c => ({
            product_id: c.id,
            quantity: c.qty * c.factor,
            price: c.price / c.factor,
            subtotal: c.price * c.qty,
        })),
        total_amount: total,
        paid_amount: paid,
        payment_method: method,
    };

    const btn = document.getElementById('btnConfirm');
    btn.disabled = true; btn.textContent = '⏳ Memproses...';

    fetch('{{ route("kasir.store") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(payload),
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            closePay();
            document.getElementById('successChange').textContent = 'Rp ' + fmt(change);
            document.getElementById('successOverlay').classList.add('show');
        } else { alert('Gagal: ' + d.message); }
    })
    .catch(() => alert('Gagal menghubungi server.'))
    .finally(() => { btn.disabled = false; btn.textContent = '✅ Konfirmasi'; });
}

function newTrx() {
    cart = []; renderCart();
    document.getElementById('successOverlay').classList.remove('show');
}

function fmt(n) { return Math.round(n).toLocaleString('id-ID'); }
</script>
</body>
</html>
