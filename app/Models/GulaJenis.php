<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GulaJenis extends Model
{
    protected $table = 'gula_jenis';

    protected $fillable = ['nama', 'keterangan', 'status'];

    public function produks() { return $this->hasMany(GulaProduk::class, 'jenis_id'); }

    public function scopeAktif($query) { return $query->where('status', 'aktif'); }
}
