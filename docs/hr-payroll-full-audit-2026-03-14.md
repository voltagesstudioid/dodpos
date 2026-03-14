# Audit Lengkap HR & Payroll (2026-03-14)

Dokumen ini melengkapi audit menu HR & Payroll dengan fokus pada **logika kerja**, **alur proses**, **validasi data**, **integrasi antar-modul**, serta **pengujian fungsional, integrasi, dan performa**.

Submenu yang termasuk dalam parent menu **🧑‍🤝‍🧑 HR & Payroll** (khusus supervisor) berada di:
- Sidebar: [app.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/layouts/app.blade.php#L700-L716)
- Route group SDM: [web.php](file:///c:/xampp8.2/htdocs/dodpos/routes/web.php#L684-L798)

## 1) Mekanisme Akses (Hak Akses)

Sistem menggunakan `Gate::before()`:
- Supervisor selalu `allow`
- Selain supervisor diputuskan oleh `RoleAbilities::allows(role, ability)`

Referensi:
- [AppServiceProvider.php](file:///c:/xampp8.2/htdocs/dodpos/app/Providers/AppServiceProvider.php#L31-L45)
- [RoleAbilities.php](file:///c:/xampp8.2/htdocs/dodpos/app/Support/RoleAbilities.php)

Catatan requirement:
- Saat ini akses SDM sepenuhnya **role-based**; belum ada konsep **department-based permission**.

## 2) Daftar Submenu yang Diaudit

1. Data Karyawan → `sdm.karyawan.*`
2. Absensi Harian → `sdm.absensi.index`
3. Rekap Absensi Bulanan → `sdm.absensi.monthly` + export `sdm.absensi.monthly.export`
4. Cuti & Libur → `sdm.cuti.*` dan `sdm.libur.*`
5. Potongan & Bonus → `sdm.potongan.*` + delete bonus `sdm.bonus.destroy`
6. Penggajian → `sdm.penggajian.*`
7. Performa → `sdm.performa.*`

## 3) Analisis Alur Proses & Validasi per Submenu

### A) Data Karyawan
**Tujuan**
- CRUD data master karyawan (profil + gaji pokok + uang makan/kehadiran per hari), dan relasi ke akun user.

**Alur**
- List + filter (q/role/has_account) → `EmployeeController@index`
- Export CSV → `EmployeeController@export`
- Create/Update → `store/update` dengan validasi numeric untuk salary/allowance
- Link/unlink user → `linkUser/unlinkUser`
- Import akun → membuat employee dari users yang belum punya employee

**Validasi utama**
- `name` wajib
- `basic_salary`, `daily_allowance`: numeric min 0

Referensi:
- [EmployeeController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/EmployeeController.php#L141-L166)

### B) Absensi Harian
**Tujuan**
- Supervisi absensi harian: lihat daftar, input manual, update, link fingerprint.

**Alur penting**
- List harian + agregasi bulan berjalan → `AttendanceController@index`
- Input manual → `storeManual`
  - Wajib selfie masuk/pulang jika status hadir/telat dan jam diisi.
  - Menghitung `late_minutes` dan normalisasi status menjadi `late/present`.
- Update record → `update` dengan aturan selfie mirip input manual.
- Lihat selfie → `selfie()` dengan guard supervisor/owner.
- Sync fingerprint → `sync()` ada pre-check port dan error handling.

Validasi & aturan:
- Status hanya `present,late,absent,izin,sakit`
- Foto wajib pada status hadir/telat saat jam diisi (selfie_in/out)

Referensi:
- [AttendanceController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/AttendanceController.php#L566-L796)

### C) Rekap Absensi Bulanan
**Tujuan**
- Rekap absensi berdasarkan hari kerja aktif (calendar mode).

**Alur**
- `monthly()` menghitung:
  - hari kerja aktif (workingDates)
  - count status attendance per user
  - missing days, paid/unpaid leave days
  - work_hours, late_minutes, overtime_minutes (agregat)
- `monthlyExport()` menghasilkan CSV stream dengan kolom recap.

Referensi:
- [AttendanceController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/AttendanceController.php#L83-L387)

### D) Cuti & Libur
**Cuti**
- `store`: create pending (supervisor bisa input untuk karyawan)
- `approve/reject`: ubah status + set approver
- `destroy`: mencegah delete bila sudah approved

**Libur/Kalender**
- `store`: input tanggal libur/working day override (unique by date via validasi)
- `generateMonth`: generate kalender bulan berdasarkan working days mode
- `update/destroy`: edit dan hapus tanggal

Referensi:
- [LeaveRequestController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/LeaveRequestController.php)
- [HolidayController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/HolidayController.php)

### E) Potongan & Bonus
**Tujuan**
- CRUD item potongan dan bonus, menjadi input penggajian.

**Alur**
- `store` menerima `type=potongan|bonus` dan menulis ke tabel berbeda:
  - `sdm_deductions` atau `sdm_bonuses`
- `destroy` untuk potongan, `destroyBonus` untuk bonus

Temuan bug yang sudah diperbaiki:
- Route delete bonus sebelumnya belum ada. Sudah ditambahkan:
  - [web.php](file:///c:/xampp8.2/htdocs/dodpos/routes/web.php#L791-L797)

Referensi:
- [PotonganGajiController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PotonganGajiController.php)

### F) Penggajian
**Tujuan**
- Generate slip gaji per bulan (berbasis absensi, cuti, kalender kerja, potongan, bonus).
- Lock/unlock dan adjust manual.
- Print slip.

**Alur utama**
- `generate(month)`:
  - workingDates ditentukan oleh setting
  - absensi di-filter hanya tanggal workingDates
  - leave approved mengurangi missing; unpaid leave menambah potongan tidak hadir
  - deductions/bonuses di-sum per user
  - skip slip jika locked
- `adjust`:
  - tidak boleh bila locked
  - update incentive/performance + override komponen dan recalculation net salary
- `destroy`: tidak boleh bila locked
- `print`: supervisor bisa print semua; non-supervisor hanya milik sendiri

Referensi:
- [PenggajianController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PenggajianController.php)

### G) Performa
**Tujuan**
- Ranking berbasis payroll `net_salary` bulan tertentu.
**Catatan**
- Ini bukan KPI/target sales; hanya urutan THP.

Referensi:
- [PerformaController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PerformaController.php)

## 4) Pengujian (Fungsional & Integrasi)

### A) Smoke test navigasi & akses
- [HrPayrollMenuAuditTest.php](file:///c:/xampp8.2/htdocs/dodpos/tests/Feature/HrPayrollMenuAuditTest.php)
  - Supervisor: menu muncul + halaman dapat dibuka (200 OK)
  - Non-supervisor: HR & Payroll ditolak (403), SDM Saya tetap bisa (200 OK jika employee aktif)
  - Delete potongan dan bonus berhasil (redirect + DB missing)

### B) Integration test CRUD & laporan per submenu
- [HrPayrollFunctionalTest.php](file:///c:/xampp8.2/htdocs/dodpos/tests/Feature/HrPayrollFunctionalTest.php)
  - Karyawan: create/update/export CSV
  - Cuti: create→approve, larangan delete approved, self-cancel cuti pending
  - Libur: store/update/generate/delete
  - Potongan & Bonus: store bonus/potongan + delete keduanya
  - Absensi: validasi selfie wajib + update status + export rekap CSV
  - Penggajian: generate→print→lock (blok adjust/delete)→unlock→adjust→delete

## 5) Evaluasi Performa (Inspection + Hardening)

### A) Titik query berat potensial
- Rekap bulanan absensi dan export memproses banyak user * workingDates.
- Generate payroll memproses agregasi untuk semua karyawan aktif.
- Sync fingerprint melakukan loop seluruh log dari mesin, lalu query per record (potensi besar).

### B) Perbaikan yang diterapkan
Ditambahkan index untuk query yang sering dipakai (filter per bulan/tanggal/user), agar beban turun di data besar:
- Migration: [add_indexes_for_hr_payroll_performance.php](file:///c:/xampp8.2/htdocs/dodpos/database/migrations/2026_03_14_210000_add_indexes_for_hr_payroll_performance.php)

Index mencakup:
- `attendances`: (date,user_id), (user_id,date), (fingerprint_id,date)
- `sdm_payrolls`: (period_year,period_month), (user_id,period_year,period_month)
- `sdm_deductions`, `sdm_bonuses`: (user_id,date) dan (date,user_id)
- `sdm_leave_requests`: (user_id,status), (start_date,end_date)
- `sdm_holidays`: (date)

## 6) Temuan & Rekomendasi Perbaikan

### Critical (P0)
- Route delete bonus pada Potongan & Bonus: **sudah diperbaiki** (lihat web.php).

### High (P1)
- Department-based permission belum ada. Jika requirement mensyaratkan “staf HR non-supervisor”, perlu:
  - tambah field department (di employee/user)
  - mapping ability berdasarkan role+department

### Medium (P2)
- Hardening error message: beberapa endpoint menampilkan message exception mentah (mis. sync/generate). Disarankan log internal + pesan generik.
- Guardrail kalender manual: jika calendar mode manual tapi kalender belum dibuat, workingDays bisa 0. Disarankan warning di UI.

## 7) Referensi Audit Terkait
- Audit menu (ringkas): [hr-payroll-menu-audit-2026-03-14.md](file:///c:/xampp8.2/htdocs/dodpos/docs/hr-payroll-menu-audit-2026-03-14.md)
- Audit penggajian: [payroll-audit-2026-03-14.md](file:///c:/xampp8.2/htdocs/dodpos/docs/payroll-audit-2026-03-14.md)
- Audit uang makan: [meal-allowance-audit-2026-03-14.md](file:///c:/xampp8.2/htdocs/dodpos/docs/meal-allowance-audit-2026-03-14.md)

