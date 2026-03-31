@extends('layouts.sales-mobile')
@section('title','Input Penjualan')
@section('header-title','Input Penjualan')
@section('header-subtitle','Step 2 dari 2 — Produk & Pembayaran')

@section('back-button')
<a href="{{ route('sales.penjualan.create') }}" style="width:34px;height:34px;border:1px solid var(--border);background:var(--bg-elevated);border-radius:10px;display:flex;align-items:center;justify-content:center;">
    <i data-lucide="arrow-left" style="width:16px;height:16px;color:rgba(255,255,255,0.7);"></i>
</a>
@endsection

@section('content')
<div style="padding:16px 16px 130px;">

    {{-- STEP INDICATOR --}}
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
        <div style="flex:1;height:3px;background:#7C3AED;border-radius:99px;"></div>
        <div style="flex:1;height:3px;background:#7C3AED;border-radius:99px;"></div>
        <span style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;white-space:nowrap;">2 / 2</span>
    </div>

    {{-- PELANGGAN BADGE --}}
    <div style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.25);border-radius:16px;margin-bottom:16px;">
        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#7C3AED,#5B21B6);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;color:#fff;flex-shrink:0;" id="pelanggan-avatar">?</div>
        <div>
            <div style="font-size:10px;color:#A78BFA;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Pelanggan</div>
            <div id="pelanggan-nama" style="font-size:14px;font-weight:700;color:#fff;margin-top:2px;">Memuat...</div>
            <div id="pelanggan-alamat" style="font-size:11px;color:rgba(255,255,255,0.4);">-</div>
        </div>
    </div>

    {{-- PRODUK --}}
    <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:10px;">Pilih Produk</div>
    <div id="produk-list" style="margin-bottom:16px;">
        <div class="skeleton" style="height:64px;margin-bottom:8px;"></div>
        <div class="skeleton" style="height:64px;"></div>
    </div>

    {{-- QUANTITY --}}
    <div style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;padding:18px 20px;margin-bottom:12px;">
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;">Jumlah</div>
        <div style="display:flex;align-items:center;justify-content:center;gap:20px;">
            <button onclick="updateQty(-1)" style="width:48px;height:48px;border:1px solid var(--border);background:var(--bg-base);border-radius:14px;display:flex;align-items:center;justify-content:center;cursor:pointer;" class="tap">
                <i data-lucide="minus" style="width:20px;height:20px;color:rgba(255,255,255,0.6);"></i>
            </button>
            <input type="number" id="qty-input" value="1" min="1"
                style="width:80px;text-align:center;font-size:28px;font-weight:800;color:#fff;background:transparent;border:none;border-bottom:2px solid #7C3AED;outline:none;padding:4px 0;"
                onchange="calculateTotal()">
            <button onclick="updateQty(1)" style="width:48px;height:48px;background:linear-gradient(135deg,#7C3AED,#5B21B6);border:none;border-radius:14px;display:flex;align-items:center;justify-content:center;cursor:pointer;" class="tap">
                <i data-lucide="plus" style="width:20px;height:20px;color:#fff;"></i>
            </button>
        </div>
    </div>

    {{-- PAYMENT TYPE --}}
    <div style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;padding:18px 20px;margin-bottom:12px;">
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;">Tipe Pembayaran</div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
            <button onclick="setPaymentType('tunai')" id="btn-tunai" class="tap"
                style="padding:12px 8px;border-radius:12px;border:2px solid #7C3AED;background:rgba(124,58,237,0.15);color:#A78BFA;font-size:13px;font-weight:600;cursor:pointer;">
                💵 Tunai
            </button>
            <button onclick="setPaymentType('hutang')" id="btn-hutang" class="tap"
                style="padding:12px 8px;border-radius:12px;border:2px solid var(--border);background:transparent;color:rgba(255,255,255,0.4);font-size:13px;font-weight:600;cursor:pointer;">
                📋 Hutang
            </button>
            <button onclick="setPaymentType('transfer')" id="btn-transfer" class="tap"
                style="padding:12px 8px;border-radius:12px;border:2px solid var(--border);background:transparent;color:rgba(255,255,255,0.4);font-size:13px;font-weight:600;cursor:pointer;">
                📱 Transfer
            </button>
        </div>
    </div>

    {{-- TUNAI INPUT --}}
    <div id="tunai-section" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:18px;padding:18px 20px;margin-bottom:12px;">
        <div style="font-size:12px;font-weight:600;color:rgba(255,255,255,0.3);text-transform:uppercase;letter-spacing:0.8px;margin-bottom:14px;">Uang Diterima</div>
        <div style="position:relative;">
            <span style="position:absolute;left:16px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.4);font-weight:600;font-size:14px;">Rp</span>
            <input type="number" id="uang-diterima" placeholder="0" oninput="calculateKembalian()"
                style="width:100%;padding:14px 16px 14px 48px;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:14px;color:#fff;font-size:18px;font-weight:700;outline:none;">
        </div>
        <div id="kembalian-section" style="display:none;margin-top:12px;padding:12px 16px;background:rgba(5,150,105,0.1);border:1px solid rgba(52,211,153,0.2);border-radius:12px;">
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13px;font-weight:500;color:#34D399;">Kembalian</span>
                <span id="kembalian-amount" style="font-size:18px;font-weight:800;color:#34D399;">Rp 0</span>
            </div>
        </div>
    </div>

    {{-- TOTAL --}}
    <div class="card-glow" style="padding:18px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;margin-bottom:4px;">Total Pembayaran</div>
                <div id="total-amount" style="font-size:26px;font-weight:800;color:#fff;letter-spacing:-1px;">Rp 0</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:11px;color:rgba(255,255,255,0.4);font-weight:500;margin-bottom:4px;">Harga Satuan</div>
                <div id="harga-satuan" style="font-size:13px;font-weight:600;color:#A78BFA;">Rp 0</div>
            </div>
        </div>
    </div>
</div>

{{-- SUBMIT --}}
<div style="position:fixed;bottom:0;left:0;right:0;padding:16px;background:linear-gradient(to top,var(--bg-base) 60%,transparent);z-index:45;">
    <button onclick="simpanPenjualan()" class="tap"
        style="width:100%;padding:16px;border:none;border-radius:16px;font-size:15px;font-weight:700;color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#059669,#065F46);box-shadow:0 4px 20px rgba(5,150,105,0.3);">
        <i data-lucide="check-circle" style="width:20px;height:20px;"></i>
        Simpan Penjualan
    </button>
</div>
@endsection

@push('scripts')
<script>
let pelanggan = null;
let produkData = [];
let selectedProduk = null;
let qty = 1;
let paymentType = 'tunai';

document.addEventListener('DOMContentLoaded', async function() {
    // Load selected pelanggan
    const stored = sessionStorage.getItem('selected_pelanggan');
    if (!stored) {
        window.location.href = '{{ route('sales.penjualan.create') }}';
        return;
    }
    
    pelanggan = JSON.parse(stored);
    document.getElementById('pelanggan-nama').textContent = pelanggan.nama_toko;
    document.getElementById('pelanggan-alamat').textContent = pelanggan.alamat;
    document.getElementById('pelanggan-avatar').textContent = pelanggan.nama_toko.charAt(0).toUpperCase();
    
    await loadProduk();
    lucide.createIcons();
});

async function loadProduk() {
    try {
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/produk`);
        
        if (response.ok) {
            const result = await response.json();
            produkData = result.data || [];
        } else {
            if (window.salesPWA) {
                produkData = await window.salesPWA.getOfflineData('produk');
            }
        }
        renderProdukList();
    } catch (error) {
        console.error('Failed to load produk:', error);
        if (window.salesPWA) {
            produkData = await window.salesPWA.getOfflineData('produk');
            renderProdukList();
        }
    }
}

function renderProdukList() {
    const container = document.getElementById('produk-list');
    if (produkData.length === 0) {
        container.innerHTML = `<div style="text-align:center;padding:32px;color:rgba(255,255,255,0.3);font-size:13px;">Tidak ada produk tersedia</div>`;
        return;
    }
    container.innerHTML = produkData.map((p, i) => {
        const sel = selectedProduk?.id === p.id;
        return `<div onclick="selectProduk(${i})" class="tap-sm" style="display:flex;align-items:center;gap:12px;padding:14px 16px;background:${sel?'rgba(5,150,105,0.12)':'var(--bg-elevated)'};border:2px solid ${sel?'rgba(52,211,153,0.4)':'var(--border)'};border-radius:16px;margin-bottom:8px;cursor:pointer;transition:all 0.15s;">
            <div style="width:44px;height:44px;border-radius:13px;background:${sel?'linear-gradient(135deg,#059669,#065F46)':'rgba(255,255,255,0.06)'};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="package" style="width:20px;height:20px;color:${sel?'#fff':'rgba(255,255,255,0.3)'};"></i>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:600;color:#fff;">${p.nama}</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:2px;">Stok: ${p.stok||0} ${p.satuan||'pcs'}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-size:14px;font-weight:700;color:${sel?'#34D399':'#fff'}; ">${formatRupiah(p.harga_jual)}</div>
                ${sel?'<i data-lucide="check-circle" style="width:16px;height:16px;color:#34D399;margin-top:4px;"></i>':''}
            </div>
        </div>`;
    }).join('');
    lucide.createIcons();
}

function selectProduk(index) {
    selectedProduk = produkData[index];
    document.getElementById('harga-satuan').textContent = formatRupiah(selectedProduk.harga_jual);
    renderProdukList();
    calculateTotal();
}

function updateQty(delta) {
    const input = document.getElementById('qty-input');
    qty = Math.max(1, parseInt(input.value || 1) + delta);
    input.value = qty;
    calculateTotal();
}

function setPaymentType(type) {
    paymentType = type;
    ['tunai','hutang','transfer'].forEach(t => {
        const b = document.getElementById(`btn-${t}`);
        if(t === type) {
            b.style.borderColor = '#7C3AED'; b.style.background = 'rgba(124,58,237,0.15)'; b.style.color = '#A78BFA';
        } else {
            b.style.borderColor = 'var(--border)'; b.style.background = 'transparent'; b.style.color = 'rgba(255,255,255,0.4)';
        }
    });
    document.getElementById('tunai-section').style.display = type === 'tunai' ? 'block' : 'none';
}

function calculateTotal() {
    if (!selectedProduk) return;
    
    const total = selectedProduk.harga_jual * qty;
    document.getElementById('total-amount').textContent = formatRupiah(total);
    
    if (paymentType === 'tunai') {
        calculateKembalian();
    }
}

function calculateKembalian() {
    if (!selectedProduk) return;
    
    const total = selectedProduk.harga_jual * qty;
    const diterima = parseInt(document.getElementById('uang-diterima').value) || 0;
    const kembalian = diterima - total;
    
    const section = document.getElementById('kembalian-section');
    section.style.display = (kembalian >= 0 && diterima > 0) ? 'block' : 'none';
    if(kembalian >= 0 && diterima > 0) document.getElementById('kembalian-amount').textContent = formatRupiah(kembalian);
}

async function simpanPenjualan() {
    if (!selectedProduk) {
        showToast('Pilih produk terlebih dahulu', 'error');
        return;
    }
    
    if (!pelanggan) {
        showToast('Data pelanggan tidak valid', 'error');
        return;
    }
    
    const total = selectedProduk.harga_jual * qty;
    const diterima = paymentType === 'tunai' ? (parseInt(document.getElementById('uang-diterima').value) || 0) : 0;
    
    if (paymentType === 'tunai' && diterima < total) {
        showToast('Uang diterima kurang dari total', 'error');
        return;
    }
    
    showLoading(true);
    
    const data = {
        localId: `penj_${Date.now()}`,
        tanggal_jual: new Date().toISOString().split('T')[0],
        pelanggan_id: pelanggan.id,
        produk_id: selectedProduk.id,
        jumlah: qty,
        harga_satuan: selectedProduk.harga_jual,
        total: total,
        tipe_bayar: paymentType,
        latitude: null,
        longitude: null,
        syncStatus: 'pending',
        createdAt: Date.now()
    };
    
    try {
        // Try online first
        const division = '{{ auth()->user()->division ?? "minyak" }}';
        const response = await fetch(`/api/v1/${division}/penjualan`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        if (response.ok) {
            showToast('Penjualan berhasil disimpan', 'success');
            sessionStorage.removeItem('selected_pelanggan');
            window.location.href = '{{ route('sales.dashboard') }}';
        } else {
            throw new Error('Server error');
        }
    } catch (error) {
        // Save offline
        if (window.salesPWA) {
            await window.salesPWA.saveOffline(data, 'penjualan_pending');
            showToast('Penjualan tersimpan (offline mode)', 'info');
            sessionStorage.removeItem('selected_pelanggan');
            window.location.href = '{{ route('sales.dashboard') }}';
        } else {
            showToast('Gagal menyimpan penjualan', 'error');
        }
    } finally {
        showLoading(false);
    }
}

function formatRupiah(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}
</script>
@endpush
