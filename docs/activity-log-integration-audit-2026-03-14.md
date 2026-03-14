# Audit Integrasi Log Aktivitas (2026-03-14)

Dokumen ini memverifikasi apakah menu **Log Aktivitas** sudah terintegrasi dengan menu-menu lain, sehingga setiap aksi pengguna tercatat konsisten (timestamp, user ID, jenis aktivitas, dan detail).

## 1) Mekanisme Logging yang Ditemukan

Sistem menggunakan paket **Spatie Activitylog**:
- Tabel: `activity_log`
- Field penting: `created_at`, `causer_id`, `event`, `subject_type`, `subject_id`, `properties`, `description`
- Controller viewer: [ActivityLogController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/ActivityLogController.php)
- Halaman viewer: [index.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/pengaturan/log/index.blade.php)

### A) Model-level log (detail perubahan)
Beberapa model sudah memakai `LogsActivity` sehingga event `created/updated/deleted` dan detail `old/attributes` tercatat:
- [User.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/User.php)
- [Customer.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/Customer.php)
- [Supplier.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/Supplier.php)
- [Product.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/Product.php)
- [ProductStock.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/ProductStock.php)
- [Transaction.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/Transaction.php)
- [PurchaseOrder.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/PurchaseOrder.php)
- [StockMovement.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/StockMovement.php)
- [StockTransfer.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/StockTransfer.php)

### B) Request-level log (cakupan semua menu)
Untuk memastikan **aksi di semua menu** tetap tercatat meski modelnya belum memakai `LogsActivity`, ditambahkan logger pada level request:
- Middleware: [LogWebActivity.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Middleware/LogWebActivity.php)
- Terpasang pada group `web`: [app.php](file:///c:/xampp8.2/htdocs/dodpos/bootstrap/app.php#L16-L27)

Logger ini mencatat setiap request **POST/PUT/PATCH/DELETE** yang dilakukan user login, dengan properties minimal:
- Request: method, path, route name, route params, input (disanitasi)
- Response: status_code, duration_ms, outcome (success/error)

## 2) Menu Log Aktivitas (Viewer) – Konektivitas dan Format

Viewer mengambil data:
- `Activity::with('causer')->latest()`
- Filter optional: user_id, event, date range

Perubahan yang dilakukan:
- Menambahkan opsi filter event `performed` pada dropdown (untuk aksi non-CRUD seperti generate/sync/lock).
- Jika event bukan `created/updated/deleted`, viewer menampilkan JSON properties (mis. route/method/status/duration).

Referensi:
- [index.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/pengaturan/log/index.blade.php)

## 3) Skenario Pengujian & Hasil

### A) Integration test otomatis
Test dibuat untuk memverifikasi:
- Aksi update data (contoh: update pelanggan) menghasilkan entri log dengan:
  - `causer_id` terisi
  - `event` terisi
  - `properties.request.method` dan `properties.response.status_code/duration_ms` ada
- Halaman viewer mendukung filter `performed`

Test:
- [ActivityLogIntegrationTest.php](file:///c:/xampp8.2/htdocs/dodpos/tests/Feature/ActivityLogIntegrationTest.php)

Status:
- Lulus di test suite.

### B) Validasi field minimal (yang dijamin ada)
Dengan middleware, minimal selalu tersedia:
- Timestamp: `created_at`
- User ID: `causer_id`
- Jenis aktivitas: `event` (created/updated/deleted/performed)
- Rincian: `properties.request` dan `properties.response`

## 4) Status Integrasi per Menu (Ringkas)

Klasifikasi:
- **Detail**: model-level `LogsActivity` (old/new)
- **Tercatat**: request-level via middleware (route/method/status/duration)

Ringkasan:
- Semua menu yang melakukan perubahan data via web (POST/PUT/PATCH/DELETE) sekarang minimal **Tercatat**.
- Menu yang berbasis model yang sudah memakai `LogsActivity` juga memiliki **Detail** perubahan.

## 5) Catatan dan Rekomendasi

### A) Pengurangan noise
Request-level logging akan menambah volume log karena setiap aksi POST/PUT/PATCH/DELETE tercatat, termasuk aksi “operasional” (sync/generate).
Jika diperlukan, dapat ditambahkan:
- Filter allowlist/denylist route tertentu
- Filter hanya status sukses (2xx/3xx)
- Tambah kolom filter `log_name` pada viewer agar log `web` bisa dipisahkan dari log model `default`

### B) Peningkatan detail perubahan untuk modul non-logged
Jika dibutuhkan detail field old/new untuk lebih banyak modul, rekomendasinya:
- Tambah `LogsActivity` ke model-model yang menjadi inti tiap menu.

