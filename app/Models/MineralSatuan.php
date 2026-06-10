<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MineralSatuan extends Model
{
    protected $table = 'mineral_satuan';

    protected $fillable = ['nama', 'singkatan', 'urutan', 'status'];

    /**
     * Get active satuan list for dropdown.
     */
    public static function getAktifList()
    {
        return static::where('status', 'aktif')->orderBy('urutan')->orderBy('nama')->get();
    }
}
