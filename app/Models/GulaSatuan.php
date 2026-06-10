<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaSatuan extends Model
{
    protected $table = 'gula_satuan';

    protected $fillable = ['nama', 'singkatan', 'keterangan', 'status'];

    public function produks() { return $this->hasMany(GulaProduk::class, 'satuan_id'); }

    public function scopeAktif($query) { return $query->where('status', 'aktif'); }
}
