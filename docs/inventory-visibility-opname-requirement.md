## Inventory visibility vs opname requirement (Admin3/Admin4)

### Background
Some deployments accidentally ended up in a state where warehouse admin users (roles `admin3` and `admin4`) could not access stock-related pages until they completed a stock opname. This is a critical failure mode because it blocks day-to-day warehouse operations.

### Goal
- Keep inventory pages readable at all times (no visibility lock).
- Keep the operational requirement: Admin3/Admin4 must complete stock opname before ending their shift.

### Implementation

#### 1) Inventory pages remain accessible
Stock visibility is still governed by the `view_stok_gudang` / `view_laporan_stok` abilities.
No additional “opname done” gating is applied to stock pages.

Regression coverage is provided by feature tests that verify Admin3/Admin4 can open the stock page without any opname.

#### 2) Enforce opname at shift end (check-out)
The enforcement point is moved to the end-of-shift action: Attendance self check-out (`Absen pulang`).

When `action=out` for roles `admin3`/`admin4`, the system checks for a `StockOpnameSession` for the same date and warehouse with status `submitted` or `approved`.

If missing, check-out is rejected with a clear error message.

Warehouse mapping:
- `admin3` -> warehouse_id `1`
- `admin4` -> warehouse_id `2`

#### 3) Monitoring / early warning
Authorization failures for stock-related paths are logged for roles `admin3` and `admin4`:
- `gudang/stok`
- `gudang/expired`
- `gudang/minstok`
- `laporan/stok`

This provides an audit trail if a future configuration or route change accidentally blocks inventory visibility again.

### Tests
`tests/Feature/InventoryVisibilityTest.php` covers:
- Admin3/Admin4 can view stock page without opname.
- Admin3 check-out is blocked if opname not submitted.
- Admin3/Admin4 check-out succeeds after opname is submitted (including when submitted by another user in the same warehouse).
