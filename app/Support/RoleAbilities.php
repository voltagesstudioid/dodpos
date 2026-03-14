<?php

namespace App\Support;

class RoleAbilities
{
    public static function allows(string $role, string $ability): bool
    {
        $role = strtolower(trim($role));
        $ability = strtolower(trim($ability));

        if ($role === 'supervisor') {
            return true;
        }

        if ($ability === 'view_dashboard') {
            return in_array($role, ['admin_sales'], true);
        }

        if ($ability === 'manage_sesi_operasional') {
            return false;
        }

        if (str_contains($ability, 'hutang') || str_contains($ability, 'piutang') || $ability === 'view_pasgar_penagihan') {
            return false;
        }

        if (in_array($ability, ['view_pengguna', 'create_pengguna', 'edit_pengguna', 'delete_pengguna'], true)) {
            return false;
        }

        if (in_array($ability, ['view_pengaturan_toko', 'edit_pengaturan_toko', 'view_backup_restore', 'create_backup_restore', 'view_log_aktivitas', 'delete_log_aktivitas'], true)) {
            return false;
        }

        if (in_array($ability, ['view_karyawan', 'create_karyawan', 'edit_karyawan', 'delete_karyawan', 'view_absensi', 'create_absensi', 'edit_absensi', 'view_performa'], true)) {
            return false;
        }

        if (
            str_starts_with($ability, 'view_penggajian')
            || str_starts_with($ability, 'create_penggajian')
            || str_starts_with($ability, 'edit_penggajian')
            || str_starts_with($ability, 'delete_penggajian')
        ) {
            return false;
        }

        if (
            str_starts_with($ability, 'view_potongan_gaji')
            || str_starts_with($ability, 'create_potongan_gaji')
            || str_starts_with($ability, 'delete_potongan_gaji')
        ) {
            return false;
        }

        if ($role === 'admin1') {
            return in_array($ability, [
                'view_pos_kasir',
                'view_sesi_kasir',
                'view_transaksi',
                'edit_transaksi',
                'view_pelanggan',
                'view_daftar_harga',
                'view_sales_order',
                'view_laporan_penjualan',
                'view_laporan_pelanggan',
            ], true);
        }

        if ($role === 'admin2') {
            if (str_ends_with($ability, '_operasional')) {
                return true;
            }
            if (str_starts_with($ability, 'view_') && str_contains($ability, 'operasional')) {
                return true;
            }

            return in_array($ability, [
                'view_pos_kasir',
                'view_sesi_kasir',
                'view_transaksi',
                'edit_transaksi',
                'view_pelanggan',
                'view_daftar_harga',
                'view_sales_order',
                'view_laporan_penjualan',
                'view_laporan_keuangan',
                'view_laporan_supplier',
            ], true);
        }

        if ($role === 'admin_sales') {
            return in_array($ability, [
                'view_laporan_penjualan',
            ], true);
        }

        if ($role === 'admin3') {
            return in_array($ability, [
                'view_stok_gudang',
                'view_penerimaan_barang',
                'view_opname_stok',
                'create_opname_stok',
                'view_laporan_stok',
                'view_permintaan_barang',
            ], true);
        }

        if ($role === 'admin4') {
            return in_array($ability, [
                'view_stok_gudang',
                'view_pengeluaran_barang',
                'view_retur_pembelian',
                'view_laporan_pembelian',
                'view_laporan_stok',
                'view_laporan_supplier',
                'view_permintaan_barang',
                'view_opname_stok',
                'create_opname_stok',
            ], true);
        }

        if ($role === 'kasir') {
            return in_array($ability, [
                'view_transaksi',
                'view_pelanggan',
                'view_daftar_harga',
            ], true);
        }

        if ($role === 'gudang') {
            if (str_starts_with($ability, 'view_master_') || str_starts_with($ability, 'create_master_') || str_starts_with($ability, 'edit_master_') || str_starts_with($ability, 'delete_master_')) {
                return true;
            }

            return in_array($ability, [
                'view_stok_gudang',
                'view_penerimaan_barang',
                'view_pengeluaran_barang',
                'view_laporan_stok',
            ], true);
        }

        if ($role === 'pasgar') {
            if (str_starts_with($ability, 'view_pasgar_') || str_starts_with($ability, 'create_pasgar_') || str_starts_with($ability, 'edit_pasgar_') || str_starts_with($ability, 'delete_pasgar_')) {
                return true;
            }

            return false;
        }

        if (str_starts_with($role, 'sales_')) {
            if ($role === 'sales_kanvas') {
                return str_starts_with($ability, 'view_kanvas_') || str_starts_with($ability, 'create_kanvas_') || str_starts_with($ability, 'edit_kanvas_') || str_starts_with($ability, 'delete_kanvas_');
            }
            if ($role === 'sales_gula') {
                return str_starts_with($ability, 'view_gula_') || str_starts_with($ability, 'create_gula_') || str_starts_with($ability, 'edit_gula_') || str_starts_with($ability, 'delete_gula_');
            }
            if ($role === 'sales_mineral') {
                return str_starts_with($ability, 'view_mineral_') || str_starts_with($ability, 'create_mineral_') || str_starts_with($ability, 'edit_mineral_') || str_starts_with($ability, 'delete_mineral_');
            }
            if ($role === 'sales_minyak') {
                return str_starts_with($ability, 'view_minyak_') || str_starts_with($ability, 'create_minyak_') || str_starts_with($ability, 'edit_minyak_') || str_starts_with($ability, 'delete_minyak_');
            }

            return false;
        }

        return false;
    }
}
