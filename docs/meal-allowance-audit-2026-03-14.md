# Audit Modul Uang Makan / Uang Kehadiran (2026-03-14)

Audit ini memverifikasi implementasi logika uang makan (di sistem disebut “Uang Kehadiran per Hari”) agar sesuai ketentuan:
1) Uang makan hanya dibayar pada **hari kerja aktif**  
2) Tidak ada uang makan pada **hari libur** atau saat karyawan **tidak masuk**  
3) Ada pemotongan otomatis uang makan saat **telat**, sesuai ketentuan

## 1) Cakupan Kode yang Diverifikasi

**Sumber nilai uang makan per hari (master karyawan)**
- Input: `sdm_employees.daily_allowance`
  - Form: [create.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/sdm/karyawan/create.blade.php#L53-L64)
  - Persist: [EmployeeController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/EmployeeController.php#L141-L166)

**Kebijakan hari kerja & potongan telat (setting toko)**
- Setting fields: [StoreSetting.php](file:///c:/xampp8.2/htdocs/dodpos/app/Models/StoreSetting.php)
- UI pengaturan: [toko.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/pengaturan/toko.blade.php#L276-L323)
  - `sdm_working_days_mode` (mon_sat / mon_fri)
  - `sdm_calendar_mode` (auto / manual)
  - `sdm_late_meal_cut_mode` (none / full / percent / fixed)
  - `sdm_late_meal_cut_value`

**Perhitungan uang makan dan potongan telat**
- Payroll generator: [PenggajianController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PenggajianController.php#L99-L229)
- Kalender hari kerja: `workingDates()` [PenggajianController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PenggajianController.php#L438-L494)
- Rumus potongan telat: `lateMealPenalty()` [PenggajianController.php](file:///c:/xampp8.2/htdocs/dodpos/app/Http/Controllers/Sdm/PenggajianController.php#L496-L515)

**Tampilan hasil**
- Slip gaji print menampilkan uang makan (bersih) + breakdown per hari dan potongan telat:
  - [print.blade.php](file:///c:/xampp8.2/htdocs/dodpos/resources/views/sdm/penggajian/print.blade.php#L119-L137)

## 2) Capture Logika Bisnis (yang berlaku saat ini)

### Definisi “Hari Kerja Aktif”
Sistem menentukan daftar hari kerja **per bulan**:
- Jika `sdm_calendar_mode=auto`:
  - Hari kerja mengikuti `sdm_working_days_mode` (Senin–Jumat atau Senin–Sabtu)
  - Hari libur pada table `sdm_holidays` dengan `is_working_day=false` dikecualikan
  - Override hari kerja pada table `sdm_holidays` dengan `is_working_day=true` dipaksa masuk
- Jika `sdm_calendar_mode=manual`:
  - Hanya tanggal pada `sdm_holidays` yang `is_working_day=true` yang dianggap “hari kerja aktif”

### Kapan Uang Makan Dibayar
Pada proses `generate payroll`, absensi yang dihitung hanya absensi yang jatuh pada **workingDates**.

Komponen uang makan:
- `totalAttendance = present_days + late_days`
- `meal_allowance_gross = totalAttendance * daily_allowance`
- `late_meal_penalty = lateMealPenalty(lateDays, daily_allowance, cut_mode, cut_value)`
- `total_allowance = max(meal_allowance_gross - late_meal_penalty, 0)`

Dengan demikian:
- Hari libur (di luar workingDates) **tidak menambah** present/late → uang makan tidak dibayar
- Status selain `present/late` (`absent`, `missing`, `izin`, `sakit`) **tidak menambah** `totalAttendance` → uang makan tidak dibayar

### Mekanisme Potongan Telat
`lateMealPenalty(lateDays, mealPerDay, mode, value)`:
- `none` → 0
- `full` → `lateDays * mealPerDay`
- `percent` → `lateDays * (mealPerDay * value/100)`
- `fixed` → `lateDays * value`

## 3) Hasil Pengujian (Skenario Positif–Negatif)

Automated test dibuat untuk memverifikasi aturan (1)–(3):
- Test: [MealAllowanceAuditTest.php](file:///c:/xampp8.2/htdocs/dodpos/tests/Feature/MealAllowanceAuditTest.php)

**Ringkasan skenario**
- Manual calendar: absensi “present” pada tanggal yang tidak ditandai working day → tidak dihitung (tidak dibayar uang makan).
- Auto calendar + holiday: absensi “present” pada tanggal yang ditandai libur (`is_working_day=false`) → tidak dihitung (tidak dibayar uang makan).
- Tidak masuk: status `absent` pada working day → `totalAttendance=0`, uang makan=0.
- Telat: status `late` pada working day + mode potongan `percent` → `late_meal_penalty` dan `total_allowance` sesuai rumus.

## 4) Kesimpulan

Status implementasi terhadap requirement:
- (1) Hari kerja aktif: **OK** — absensi yang dipakai untuk uang makan hanya yang jatuh pada `workingDates`.
- (2) Libur/tidak masuk: **OK** — libur tidak masuk workingDates; tidak masuk tidak menambah `present+late`.
- (3) Potongan telat: **OK** — ada mekanisme potongan otomatis (none/full/percent/fixed).

## 5) Rekomendasi Perbaikan

**A. UX penamaan**
- Di form karyawan tertulis “Uang Kehadiran per Hari”, tetapi di slip tertulis “Uang Makan”.
- Rekomendasi: konsistenkan istilah (mis. “Uang Makan per Hari (berdasarkan hadir/telat)”).

**B. Guardrail untuk mode kalender manual**
- Jika `sdm_calendar_mode=manual` namun belum ada data `sdm_holidays.is_working_day=true` untuk bulan berjalan, hasil workingDays bisa 0 dan uang makan otomatis 0.
- Rekomendasi: tampilkan warning di halaman Penggajian/Absensi saat workingDays=0 agar supervisor sadar konfigurasi kalender belum dibuat.

**C. Ketentuan cuti/izin dibayar**
- Saat ini cuti/izin/sakit (termasuk paid leave) tidak menambah uang makan karena basisnya hanya `present+late`.
- Jika aturan bisnis menghendaki paid leave tetap mendapat uang makan, perlu penambahan setting basis uang makan.

