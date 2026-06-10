<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinyakJenis extends Model
{
    protected $table = 'minyak_jenis';

    protected $fillable = ['nama', 'urutan', 'status'];

    /**
     * Get active jenis list for dropdown.
     */
    public static function getAktifList()
    {
        return static::where('status', 'aktif')->orderBy('urutan')->orderBy('nama')->get();
    }
}
