# Audit Modul Penggajian (2026-03-14)

Dokumen ini merangkum audit menyeluruh modul SDM (Penggajian) pada aplikasi DODPOS, mencakup alur perhitungan, integrasi database/absensi, tampilan frontend, validasi & error handling, serta output laporan (slip gaji). Audit ini juga menambahkan uji fungsional dengan data dummy terkontrol untuk memverifikasi akurasi perhitungan.

## 1) Peta Komponen (Code Map)

**Routes**
- Penggajian (Supervisor): `/sdm/penggajian` → `sdm.penggajian.index`
- Generate payroll: `POST /sdm/penggajian/generate` → `sdm.penggajian.generate`
- Adjust komponen payroll: `PATCH /sdm/penggajian/{penggajian}/adjust` → `sdm.penggajian.adjust`
- Lock/Unlock: `POST /sdm/penggajian/{penggajian}/lock|unlock`
- Print slip: `GET /sdm/penggajian/{penggajian}/print`
- Self payroll (karyawan): `/sdm/gaji-saya` → `sdm.penggajian.self_index`

**Controllers**
- Perhitungan + generate + adjust + print: [PenggajianController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PenggajianController.php)
- Kelola potongan/bonus: [PotonganGajiController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PotonganGajiController.php)
- Rekap performa (ranking): [PerformaController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PerformaController.php)

**Views**
- Supervisor list + generate + modal adjust: [penggajian/index.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/sdm/penggajian/index.blade.php)
- Karyawan (self-service): [penggajian/self.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/sdm/penggajian/self.blade.php)
- Slip gaji (print): [penggajian/print.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/sdm/penggajian/print.blade.php)
- Potongan & bonus: [potongan/index.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/sdm/potongan/index.blade.php)

**Models**
- Payroll: [SdmPayroll.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/SdmPayroll.php)
- Karyawan: [SdmEmployee.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/SdmEmployee.php)
- Potongan: [SdmDeduction.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/SdmDeduction.php)
- Bonus/insentif: [SdmBonus.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/SdmBonus.php)
- Absensi: [Attendance.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/Attendance.php)
- Cuti: [SdmLeaveRequest.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/SdmLeaveRequest.php)
- Kalender libur: [SdmHoliday.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/SdmHoliday.php)
- Setting kebijakan SDM: [StoreSetting.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/StoreSetting.php)

**Database Schema (ringkas)**
- `sdm_employees`: `basic_salary`, `daily_allowance`
- `sdm_payrolls`: komponen payroll + override + lock
- `sdm_deductions`, `sdm_bonuses`: `date`, `description`, `amount`
- `attendances`: `status`, `late_minutes`, `overtime_minutes`, dll
- `sdm_leave_requests`: `type`, `paid`, `status`, rentang tanggal
- `sdm_holidays`: `date`, `is_working_day` (calendar override)

## 2) Alur Perhitungan & Formula yang Digunakan

Sumber utama perhitungan adalah `PenggajianController::generate()` yang:
- Menentukan kalender hari kerja via `workingDates(year, month, working_days_mode, calendar_mode)`.
- Mengambil absensi bulan berjalan: `Attendance` (`status`, `overtime_minutes`), namun hanya yang jatuh pada **hari kerja**.
- Mengambil cuti yang disetujui: `SdmLeaveRequest` status `approved`.
- Mengambil total potongan dan bonus: agregasi `SUM(amount)` per user.
- Membuat/menimpa record `SdmPayroll` kecuali yang sudah `locked_at`.

**Komponen yang dihitung**
- Gaji pokok efektif:
  - `effectiveBasicSalary = override_total_basic_salary ?? employee.basic_salary`
- Kehadiran:
  - `totalAttendance = present_days + late_days`
  - `izin/sakit/cuti paid` tidak menambah `totalAttendance`
- Uang makan (gross):
  - `mealAllowanceGross = totalAttendance * employee.daily_allowance`
- Potongan uang makan karena telat:
  - `lateMealPenalty = lateMealPenalty(lateDays, mealPerDay, cut_mode, cut_value)`
  - mode:
    - `none` → 0
    - `full` → lateDays * mealPerDay
    - `percent` → lateDays * (mealPerDay * value/100)
    - `fixed` → lateDays * value
  - `totalAllowance = max(mealAllowanceGross - effectiveLateMealPenalty, 0)`
- Lembur:
  - `overtimePay = (SUM(overtime_minutes)/60) * store_setting.sdm_overtime_rate_per_hour`
- Potongan tidak hadir:
  - `dailyBasic = effectiveBasicSalary / workingDaysCount`
  - `absenceDeductionDays = absent_days + missing_days + unpaid_leave_days`
  - `absenceDeduction = absenceDeductionDays * dailyBasic`
  - `effectiveAbsenceDeduction = override_absence_deduction ?? absenceDeduction`
- Potongan manual:
  - `totalDeductions = SUM(sdm_deductions.amount)`
- Insentif:
  - `totalBonuses = SUM(sdm_bonuses.amount)`
  - `incentiveAmount = (existing.incentive_amount > 0) ? existing.incentive_amount : totalBonuses`
- Bonus performa:
  - `performanceBonus = existing.performance_bonus` (manual)
- THP:
  - `netSalary = (basic + allowance + overtime + incentive + performanceBonus) - (deductions + absenceDeduction)`
  - Jika negatif → 0

## 3) Integrasi Database & Sistem Kehadiran

**Sumber data absensi**
- Absensi berasal dari table `attendances` dan status yang umum: `present`, `late`, `izin`, `sakit`, `absent`.
- `late` ditentukan berdasarkan `sdm_work_start_time` dan `sdm_late_grace_minutes` (logic ada di `AttendanceController`).
- `overtime_minutes` diisi manual pada panel absensi, tidak ada auto-calc overtime berbasis `work_hours`.

**Kalender kerja**
- `sdm_calendar_mode=auto`: Mon-Sat / Mon-Fri (sesuai `sdm_working_days_mode`) lalu dikurangi hari libur (`sdm_holidays.is_working_day=false`) dan ditambah override hari kerja (`is_working_day=true`).
- `sdm_calendar_mode=manual`: hanya tanggal yang diinput di `sdm_holidays` dengan `is_working_day=true` yang dianggap hari kerja.

**Cuti**
- Cuti `paid=true` mencegah tanggal tersebut dihitung sebagai `missing`, tetapi tidak menambah `totalAttendance`.
- Cuti `paid=false` dihitung sebagai `unpaid_leave_days` (menambah potongan tidak hadir), dan juga mencegah `missing`.

## 4) Frontend & Fungsionalitas

**Penggajian (Supervisor)**
- UI memungkinkan:
  - Generate payroll per bulan
  - Cetak slip
  - Edit komponen manual (insentif, bonus performa, override basic, override potongan telat makan, override potongan tidak absen)
  - Lock/Unlock slip agar tidak berubah saat generate

**Potongan & Bonus**
- UI menginput “bonus” dan “potongan” sebagai item generik (`description`, `amount`, `date`).
- Bonus dan potongan terikat ke user_id.

**Performa**
- Saat ini hanya menampilkan ranking berdasarkan `net_salary`, bukan KPI/target penjualan.

## 5) Validasi Data & Error Handling

**Generate payroll**
- Validasi: `month` wajib format `Y-m`.
- Dibungkus transaksi DB. Jika error, pesan exception ditampilkan ke pengguna (`Gagal menghitung gaji: {message}`).

**Adjust payroll**
- Validasi numeric minimum 0 untuk komponen override.
- Menolak update jika slip terkunci.

## 6) Laporan yang Dihasilkan

Slip gaji print tersedia via view `sdm.penggajian.print` dan menampilkan:
- Komponen penerimaan: gaji pokok, uang makan, lembur, bonus/insentif, bonus performa
- Komponen potongan: potongan tidak hadir/tidak absen, potongan manual itemized
- Total THP

Perubahan audit ini menambahkan logika tampilan agar rincian bonus tidak membingungkan:
- Jika `incentive_amount` sama dengan total bonus item (sdm_bonuses), maka slip akan menampilkan rincian bonus dan menyembunyikan baris “Insentif” (agar tidak terlihat seperti ada 2 komponen yang sama).

## 7) Uji Fungsional (Dummy Data)

Ditambahkan test feature: [PayrollCalculationTest.php](file:///c:/xampp8.2/htdocs/dodpos/tests/Feature/PayrollCalculationTest.php)

Test mencakup:
- Perhitungan payroll terkontrol menggunakan `sdm_calendar_mode=manual` sehingga working days bisa di-set tepat (tanpa bergantung kalender bulan sebenarnya).
- Verifikasi komponen:
  - working_days, present/late, allowance, late penalty
  - potongan tidak hadir (absent + missing + unpaid leave)
  - lembur dari overtime_minutes
  - insentif dari `sdm_bonuses`
  - total potongan manual
  - THP
- Verifikasi slip print tidak menampilkan “Insentif” bila sama dengan rincian bonus.

## 8) Temuan Utama & Rekomendasi

### A. Pajak/PPH/BPJS (GAP)
**Temuan**
- Tidak ada komponen pajak khusus di schema payroll (`sdm_payrolls`) maupun perhitungan.
- Tabel potongan bersifat generik, sehingga pajak hanya bisa dimodelkan sebagai “potongan manual”.

**Rekomendasi**
- Tambah struktur komponen pajak:
  - Opsi 1 (cepat): gunakan `sdm_deductions` dengan konvensi `description` (mis. “PPh21”, “BPJS TK”) dan SOP input per bulan.
  - Opsi 2 (lebih kuat): tambah tabel/kolom pajak terstruktur (jenis pajak, basis perhitungan, parameter PTKP, dll) dan hitung otomatis saat generate.

### B. Tunjangan Jabatan & Tunjangan Khusus (GAP sebagian)
**Temuan**
- Saat ini “tunjangan” yang otomatis hanya “uang makan” (daily_allowance).
- Tunjangan jabatan/khusus bisa dicatat sebagai `sdm_bonuses`, tetapi itu bersifat manual per tanggal dan tidak permanen per karyawan.

**Rekomendasi**
- Tambah kolom pada `sdm_employees` untuk:
  - `position_allowance`
  - `special_allowance`
  - (opsional) `transport_allowance`, dll
- Lalu kalkulasikan sebagai komponen tetap per bulan saat generate.

### C. Uang Makan: Kehadiran vs Hari Kerja (GAP konfigurasi)
**Temuan**
- Uang makan dihitung berdasarkan `present + late` saja.
- Izin/sakit/cuti paid tidak menambah uang makan, walau secara bisnis bisa jadi ingin dihitung.

**Rekomendasi**
- Tambah setting `sdm_meal_allowance_basis` misalnya:
  - `attendance_only` (present+late) [sekarang]
  - `attendance_plus_paid_leave` (tambahkan cuti paid)
  - `working_days_fixed` (working_days_count)

### D. Bonus Performa berbasis KPI/Target Penjualan (GAP)
**Temuan**
- `performance_bonus` hanya angka manual per slip.
- Modul “Performa” hanya ranking THP, bukan KPI/target.

**Rekomendasi**
- Definisikan sumber KPI:
  - KPI harian/bulanan (table `sdm_kpi_results`) atau
  - target sales per user/role yang ditarik dari transaksi penjualan (SalesOrder/Transaction)
- Buat engine perhitungan bonus performa saat generate dan simpan breakdown-nya.

### E. Error handling (Hardening)
**Temuan**
- Pesan exception mentah ditampilkan ke user pada error generate.

**Rekomendasi**
- Log error internal, tampilkan pesan generik ke user untuk keamanan dan UX.

