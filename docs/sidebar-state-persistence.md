# Sidebar State Persistence

## Ringkasan

Fitur ini menjaga **posisi (expand/collapse) grup menu sidebar** agar tetap terjaga saat:
- Pindah antar halaman (navigasi)
- Reload halaman
- Akses URL langsung (deep link)

State disimpan ke **`localStorage`** browser dengan key `dodpos_sidebar_state`.

---

## Cara Kerja

### 1. Penyimpanan State (saat user klik grup)

Setiap kali user membuka atau menutup grup menu, fungsi `toggleGroup(id)` dipanggil:

```js
function toggleGroup(id) {
    const grp = document.getElementById(id);
    if (!grp) return;
    const isOpen = grp.classList.toggle('open');
    sidebarState[id] = isOpen;        // update state di memori
    localStorage.setItem(SIDEBAR_STATE_KEY, JSON.stringify(sidebarState));  // simpan ke browser
}
```

### 2. Pemulihan State (saat halaman dimuat)

Saat `DOMContentLoaded`, state dipulihkan dengan aturan prioritas:

| Kondisi | Perilaku |
|---|---|
| Server render grup sebagai `open` (ada menu aktif) | Grup tetap open, state di-update |
| Saved state = `true`, server render tutup | Grup dibuka kembali dari localStorage |
| Saved state = `false` | Grup ditutup paksa |
| Tidak ada saved state | Ikut default server (tidak berubah) |

### 3. Deep Link / Akses URL Langsung

Saat user membuka URL spesifik (misalnya `/sdm/karyawan`), server secara otomatis me-render grup yang mengandung menu aktif sebagai `open` via Blade:

```blade
@php $sdmActive = request()->routeIs('sdm.*'); @endphp
<div class="nav-group {{ $sdmActive ? 'open' : '' }}" id="grp-sdm">
```

Logika JS kemudian mendeteksi `isBackendOpen = true` dan menyimpannya ke localStorage, sehingga grup ini tetap terbuka di halaman berikutnya juga.

### 4. Auto-scroll ke Menu Aktif

Jika halaman dibuka dari URL langsung, script otomatis meng-scroll sidebar ke posisi menu yang aktif:

```js
const activeNav = document.querySelector('.nav-item.active');
if (activeNav) {
    activeNav.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
```

---

## Format Data localStorage

```json
{
  "grp-sdm": true,
  "grp-gudang": false,
  "grp-pembelian": true
}
```

- `true` = grup terbuka
- `false` = grup ditutup manual oleh user
- (kosong/undefined) = ikut default server

---

## Konfigurasi

### Ubah Storage Key

Ganti nilai konstanta di `layouts/app.blade.php` dan `components/app-layout.blade.php`:

```js
const SIDEBAR_STATE_KEY = 'dodpos_sidebar_state'; // ganti sesuai kebutuhan
```

### Tambah Grup Menu Baru

Pastikan setiap `<div class="nav-group">` memiliki atribut `id` yang unik:

```html
<div class="nav-group {{ $aktif ? 'open' : '' }}" id="grp-nama-modul">
    <button class="nav-group-header" onclick="toggleGroup('grp-nama-modul')">
        ...
    </button>
</div>
```

### Reset State (Manual)

Untuk menghapus semua state tersimpan (debugging/testing):

```js
localStorage.removeItem('dodpos_sidebar_state');
```

---

## Menjalankan Unit Test

### Instalasi Dependency

```bash
npm install --save-dev jest jest-environment-jsdom
```

### Tambahkan config di `package.json`

```json
"jest": {
  "testEnvironment": "jsdom"
}
```

### Jalankan Test

```bash
npx jest resources/js/sidebar-state.test.js --verbose
```

### Hasil yang Diharapkan

```
PASS  resources/js/sidebar-state.test.js
  Sidebar State Persistence
    ✓ 1. toggleGroup opens a closed group and persists to localStorage
    ✓ 2. toggleGroup closes an open group and persists to localStorage
    ✓ 3. restoreState opens groups that were open in localStorage
    ✓ 4. restoreState closes groups forced closed in localStorage
    ✓ 5. deep link: server marks group open => stays open
    ✓ 6. groups without saved state remain at server default
    ✓ 7. non-existent group ID in toggleGroup does not throw
    ✓ 8. corrupted localStorage gracefully falls back to empty state
    ✓ 9. multiple groups persist independently
    ✓ 10. cross-page persistence: state written in one load is read in next

Test Suites: 1 passed, 1 total
Tests:       10 passed, 10 total
```

---

## Browser Support

| Browser | Support | Keterangan |
|---|---|---|
| Chrome 4+ | ✅ | Full support |
| Firefox 3.5+ | ✅ | Full support |
| Safari 4+ | ✅ | Full support |
| Edge 12+ | ✅ | Full support |
| IE 8+ | ✅ | Full support |
| Mobile Chrome | ✅ | Full support |
| Mobile Safari | ✅ | Full support |

Terdapat `try/catch` untuk menangani edge case apabila localStorage dinonaktifkan pengguna (Private Browsing tertentu).

---

## File yang Dimodifikasi

| File | Perubahan |
|---|---|
| `resources/views/layouts/app.blade.php` | Tambah localStorage logic di `<script>` |
| `resources/views/components/app-layout.blade.php` | Tambah localStorage logic di `<script>` |
| `resources/js/sidebar-state.test.js` | File baru - 10 unit test |
| `docs/sidebar-state-persistence.md` | Dokumentasi ini |
