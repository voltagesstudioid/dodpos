# Audit Menu HR & Payroll (2026-03-14)

Audit ini mengevaluasi menu-menu di bawah parent menu **HR & Payroll** untuk memastikan:
- Navigasi mengarah ke halaman yang benar (route akurat)
- Hak akses (permission) konsisten dengan role dan kebijakan yang berlaku
- Integrasi antar modul SDM berjalan (Absensi ↔ Cuti/Libur ↔ Potongan/Bonus ↔ Penggajian ↔ Performa)
- CRUD & pelaporan utama dapat berjalan tanpa error pada kondisi data kosong
- Ada guardrail/test agar tidak terjadi regresi

## 1) Cakupan Audit (Komponen yang Dicek)

### A. Sumber menu sidebar
- Menu utama aplikasi dirender dari: [app.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/layouts/app.blade.php#L650-L740)
- Gate/permission diputuskan via: [AppServiceProvider.php](file:///c:/xampp8.2/htdocs/dodpos/app/Providers/AppServiceProvider.php#L31-L45)
- Matriks role→ability: [RoleAbilities.php](file:///c:/xampp8.2/htdocs/dodpos/app/Support/RoleAbilities.php)

### B. Routing (HR & Payroll + SDM Saya)
- Grup route SDM: [web.php](file:///c:/xampp8.2/htdocs/dodpos/routes/web.php#L684-L798)

### C. Controller utama (integrasi antar modul)
- Karyawan: [EmployeeController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/EmployeeController.php)
- Absensi: [AttendanceController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/AttendanceController.php)
- Cuti: [LeaveRequestController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/LeaveRequestController.php)
- Libur/Kalender: [HolidayController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/HolidayController.php)
- Potongan & Bonus: [PotonganGajiController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PotonganGajiController.php)
- Penggajian: [PenggajianController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PenggajianController.php)
- Performa: [PerformaController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PerformaController.php)

## 2) Inventaris Submenu HR & Payroll (Supervisor)

Menu group label: `🧑‍🤝‍🧑 HR & Payroll`

Submenu yang diverifikasi (sesuai sidebar):
- Data Karyawan → `sdm.karyawan.index`
- Absensi → `sdm.absensi.index`
- Cuti & Libur → `sdm.cuti.index` (aktif juga untuk `sdm.libur.*`)
- Potongan & Bonus → `sdm.potongan.index`
- Penggajian → `sdm.penggajian.index`
- Performa → `sdm.performa.index`

## 3) Evaluasi Routing & Navigasi

### Hasil
- Semua route pada submenu HR & Payroll tersedia dan dapat diakses supervisor (HTTP 200) pada kondisi data kosong.
- Ditemukan 1 mismatch penting terkait Potongan & Bonus (lihat Temuan P0).

### Perubahan perbaikan yang sudah dilakukan
- Ditambahkan route delete bonus agar action di halaman Potongan & Bonus tidak gagal:
  - Route baru: `sdm.bonus.destroy` → `DELETE /sdm/bonus/{potongan}`
  - Patch: [web.php](file:///c:/xampp8.2/htdocs/dodpos/routes/web.php#L791-L797)

## 4) Evaluasi Permission (Role/Department)

### Mekanisme saat ini (aktual)
- Seluruh authorization berbasis `Gate::before()`:
  - Supervisor → selalu `allow`
  - Selain supervisor → keputusan via `RoleAbilities::allows(role, ability)` (ability yang tidak dikenal → false)
- Untuk modul HR & Payroll:
  - `RoleAbilities` secara eksplisit menolak semua ability SDM untuk non-supervisor:
    - `view_karyawan`, `view_absensi`, `view_performa`, `view_penggajian*`, `view_potongan_gaji*`, dst.

### Kesesuaian dengan requirement
- Role-based access: **OK** (konsisten; supervisor bisa, role lain tidak bisa).
- Department-based access: **GAP**
  - Tidak ada konsep department/struktur organisasi yang mempengaruhi permission di `User`/`SdmEmployee` saat ini.
  - Jika requirement bisnis mengharuskan “HR staff (department SDM) non-supervisor” punya akses terbatas, sistem perlu ditambah konsep department + mapping ability.

### SDM Saya (Self-service)
Submenu terkait SDM Saya tersedia untuk user aktif:
- Absen Saya → `sdm.absensi.self_panel`
- Cuti Saya → `sdm.cuti.self_index`
- Gaji Saya → `sdm.penggajian.self_index`
- Potongan Saya → `sdm.potongan.self_index`

Controller melakukan check tambahan: user harus punya `employee` dan `employee.active=true` (jika tidak, redirect dengan error).

## 5) Evaluasi Integrasi Antar-Module

Relasi data yang divalidasi melalui code:
- Absensi dan Cuti/Libur menjadi input untuk Penggajian:
  - Absensi: `attendances.status`, `late_minutes`, `overtime_minutes`
  - Cuti: `sdm_leave_requests` (approved)
  - Libur/kalender kerja: `sdm_holidays` + `sdm_calendar_mode` + `sdm_working_days_mode`
- Potongan & Bonus menjadi input penggajian:
  - `sdm_deductions` dan `sdm_bonuses` di-sum saat generate payroll

Status integrasi:
- Penggajian sudah menarik input dari Absensi/Cuti/Libur/Potongan/Bonus: **OK**.
- Performa saat ini adalah view ranking berbasis data payroll (net salary): **OK**, namun belum KPI/target sales (di luar scope menu).

## 6) Pengujian Fungsional (Menu, Akses, CRUD minimal, Pelaporan)

### Test otomatis yang ditambahkan
- Audit navigasi dan akses menu: [HrPayrollMenuAuditTest.php](file:///c:/xampp8.2/htdocs/dodpos/tests/Feature/HrPayrollMenuAuditTest.php)
  - Supervisor melihat menu HR & Payroll di sidebar
  - Supervisor bisa membuka semua halaman HR & Payroll (HTTP 200)
  - Non-supervisor ditolak pada halaman HR & Payroll (HTTP 403)
  - Non-supervisor tetap bisa akses halaman SDM Saya jika employee aktif (HTTP 200)
  - Supervisor bisa menghapus bonus & potongan dari modul Potongan (verifikasi DB)

### Catatan pengujian manual yang disarankan (opsional)
Untuk completeness, disarankan smoke test manual pada UI:
- Buka setiap submenu HR & Payroll, pastikan filter/search tidak error saat data kosong
- Cek export (karyawan export, absensi export) pada data kecil & besar
- Generate payroll bulan berjalan dan cek print slip

## 7) Temuan & Rekomendasi (Prioritas)

### P0 — Bug: Route delete bonus tidak tersedia (FIXED)
**Gejala**
- Tombol hapus bonus di Potongan & Bonus berpotensi 404/route not defined.

**Perbaikan**
- Tambah route `sdm.bonus.destroy` yang memanggil `PotonganGajiController::destroyBonus()`.

### P1 — Konsistensi sumber menu (Potensi teknikal debt)
**Temuan**
- Ada file lain yang tampak seperti layout menu: `resources/views/components/app-layout.blade.php`, namun `<x-app-layout>` menggunakan class component `AppLayout` yang merender `layouts.app`.
- Ini berpotensi menimbulkan kebingungan karena ada dua sumber menu/layout yang berbeda.

**Rekomendasi**
- Pastikan hanya satu sumber layout yang dipakai, dan hapus/arsipkan yang tidak terpakai.

### P1 — Department-based permission (GAP requirement)
**Temuan**
- Permission SDM 100% role-based; tidak ada “department HR” atau role HR non-supervisor.

**Rekomendasi**
- Tambah konsep department (mis. `sdm_employees.department`) dan mapping ability berdasarkan department + role.
- Alternatif cepat: buat role baru `hr_staff` dan ubah `RoleAbilities` untuk mengizinkan subset SDM.

### P2 — UX/guardrail
**Rekomendasi**
- Tampilkan hint bila user non-supervisor mencoba akses HR & Payroll (403) agar jelas sebabnya (mis. “Hubungi supervisor”).
- Tambahkan indikator status data employee aktif pada SDM Saya agar user tahu prasyarat akses.

### P2 — Performa
**Rekomendasi**
- Halaman absensi bulanan bisa menjadi berat jika user banyak. Optimasi yang mungkin:
  - pagination/stream export
  - query agregasi ter-index (index pada `attendances(date,user_id,status)`)

