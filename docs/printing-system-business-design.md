# Sistem Cetak DODPOS — Analisis Kebutuhan Bisnis & Rancangan

Dokumen ini mendefinisikan fitur cetak secara sistematis berbasis kebutuhan bisnis: jenis dokumen yang wajib tersedia, spesifikasi layout, data yang ditampilkan, aturan filter/sort, prioritas, serta standar kompatibilitas printer dan performa.

Tanggal: 2026-03-17

## 1) Ruang Lingkup

Fitur cetak dibagi 2 kelas utama:

1. **Dokumen transaksi** (output operasional, biasanya dicetak per transaksi): struk, faktur, bukti bayar, surat jalan, purchase/return.
2. **Laporan & daftar** (output manajerial/rekap, sering dicetak periodik): penjualan, pembelian, stok, keuangan, pelanggan, supplier, daftar harga.

Setiap kelas harus mendukung:
- **Preview** sebelum print (buka halaman cetak tanpa auto-print).
- **PDF export** via “Print to PDF” (kompatibel universal).
- **Export data tabular** ke **CSV** dan **Excel (XLSX)** untuk laporan/daftar.

## 2) Inventaris Jenis Dokumen (Wajib Tersedia)

### A. POS & Penjualan
1. **Struk POS (Thermal 58mm)**
   - Rute: `/print/receipt/{transaction}` (print.receipt)
   - Tujuan: bukti pembayaran pelanggan (eceran).
2. **Faktur Penjualan Grosir (A4)**
   - Rute: `/print/faktur-grosir/{transaction}` (print.faktur_grosir)
   - Tujuan: invoice grosir + rangkap internal.
3. **Sales Order (A4)**
   - Halaman: detail SO saat ini sudah punya `window.print()`.
   - Tujuan: dokumen pesanan untuk proses gudang/pengiriman.

### B. Pembelian
4. **Faktur Purchase Order (A4/A5)**
   - Rute: `/print/purchase/{order}` (print.purchase)
5. **Faktur Retur Pembelian (A4/A5)**
   - Rute: `/print/return/{retur}` (print.return)

### C. Hutang/Piutang
6. **Bukti Pembayaran Hutang Supplier (A4/A5)**
   - Rute: `/print/supplier-payment/{payment}` (print.supplier_payment)
7. **Bukti Pembayaran Piutang Pelanggan (A4/A5)**
   - Rute: `/print/customer-credit-payment/{kredit}` (print.customer_credit_payment)

### D. Distribusi / Surat Jalan
8. **Surat Jalan (A4)**
   - Contoh: Mineral Loading (SJ-Mxxxx) — halaman detail sudah memiliki tombol cetak.

### E. Laporan (Parent “Laporan”)
9. **Laporan Penjualan** (harian/bulanan; filter tanggal, kasir, metode)
10. **Laporan Pembelian** (filter tanggal, supplier, status)
11. **Laporan Stok / Daftar Inventaris** (filter gudang, kategori, pencarian)
12. **Laporan Keuangan** (filter tanggal; revenue/expense/profit)
13. **Laporan Pelanggan** (ranking piutang; pencarian)
14. **Laporan Supplier** (ranking hutang; pencarian)
15. **Daftar Harga** (lebih cocok A4 Landscape atau export Excel)

## 3) Spesifikasi Layout (Ukuran, Orientasi, Margin, Header/Footer)

Standar layout menggunakan 3 profil:

### Profil P1 — Thermal 58mm (Struk)
- Kertas: 58mm (auto height)
- Margin: 2mm
- Font: monospace (Courier New) 10–12px
- Header: nama toko, alamat, telp
- Footer: ucapan terima kasih + kebijakan retur
- Catatan: minim warna, garis putus-putus untuk dot-matrix thermal.

### Profil P2 — A4 Portrait (Dokumen & laporan ringkas)
- Kertas: A4
- Orientasi: Portrait
- Margin: 10–12mm
- Header: judul dokumen, nomor referensi, periode, timestamp cetak
- Footer: halaman (opsional), signature block (opsional)
- Font: sans-serif 10–12px; tabel 9–11px untuk menghindari terpotong.

### Profil P3 — A4 Landscape (Tabel lebar)
- Kertas: A4
- Orientasi: Landscape
- Margin: 8–10mm
- Ditujukan untuk: daftar harga, beberapa laporan stok/detail yang kolomnya banyak.

## 4) Data Wajib per Dokumen + Filter & Sorting

### Laporan Penjualan
- Data: transaksi selesai, tanggal/waktu, kasir, metode, total, bayar, kembalian, jumlah item.
- Filter: date_from/date_to, kasir_id, payment_method.
- Sort: created_at DESC.
- Export: CSV/XLSX tabular + PDF via print.

### Laporan Pembelian
- Data: PO number, tanggal order, supplier, status, total, jumlah item, pembuat.
- Filter: date_from/date_to, supplier_id, status.
- Sort: order_date DESC.
- Export: CSV/XLSX tabular + PDF via print.

### Laporan Stok / Daftar Inventaris
- Data: SKU, nama, kategori, satuan, stok global, min stok.
- Filter: warehouse_id (opsional), category_id (opsional), search (nama/SKU).
- Sort: stock DESC.
- Export: CSV/XLSX tabular + PDF via print.

### Laporan Keuangan
- Data: per tanggal (revenue, expense, profit), dan ringkasan (totalRevenue, totalPembelian, totalHPP, totalOperasional, netProfit).
- Filter: date_from/date_to.
- Sort: tanggal.
- Export: CSV/XLSX tabular + PDF via print.

### Laporan Pelanggan
- Data: nama, telepon, aktif, limit piutang, sisa limit, piutang berjalan.
- Filter: search.
- Sort: current_debt DESC, name ASC.
- Export: CSV/XLSX tabular + PDF via print.

### Laporan Supplier
- Data: nama, kontak, telepon, aktif, total tagihan, sisa hutang.
- Filter: search.
- Sort: name ASC (atau outstanding DESC untuk analitik).
- Export: CSV/XLSX tabular + PDF via print.

## 5) Peta Prioritas (Frekuensi & Urgensi Bisnis)

P1 (harian, operasional kritikal):
- Struk POS 58mm
- Faktur Grosir A4
- Surat Jalan
- Laporan Penjualan (harian)

P2 (mingguan/bulanan, kontrol bisnis):
- Laporan Keuangan
- Laporan Pembelian
- Laporan Stok/Inventaris
- Bukti pembayaran hutang/piutang

P3 (insidental/pendukung):
- Daftar Harga (lebih sering export Excel)
- Dokumen HR (penggajian/rekap) sesuai kebutuhan

## 6) Preview, Print, dan Export

### Preview
- Parameter `print=1&preview=1` menampilkan halaman “print-friendly” tanpa auto-print.
- Tanpa `preview=1`, halaman dapat auto memunculkan dialog print.

### Export
- Laporan/daftar mendukung:
  - `export=csv` (stream CSV)
  - `export=xlsx` (XLSX minimal via ZipArchive)
- PDF: “Print to PDF” pada browser.

## 7) Kompatibilitas Printer

Target perangkat:
- Dot-matrix: garis tegas, font tidak terlalu kecil, minim warna.
- Inkjet: warna OK, margin standard, header jelas.
- Laser: tajam, tabel rapat, line-height konsisten.

Prinsip desain:
- Hindari tabel terlalu lebar untuk A4 portrait; gunakan landscape bila perlu.
- Hindari `min-height:100vh` pada mode print agar tidak memicu halaman kosong.
- Gunakan ukuran font minimal 9px untuk tabel, 10–12px untuk teks utama.

## 8) Rencana Pengujian (Wajib)

Minimal 3 printer:
1) Dot-matrix / thermal (58mm atau A4 dot matrix)
2) Inkjet A4
3) Laser A4

Checklist:
- Tidak ada data terpotong pada margin.
- Header/kolom tabel terbaca jelas.
- Total halaman sesuai ekspektasi.
- Waktu render halaman print preview ≤ 5 detik untuk 1 halaman (data normal).

Catatan:
- SLA 5 detik perlu diukur di lingkungan deployment dengan data realistis.

