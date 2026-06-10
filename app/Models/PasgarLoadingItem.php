<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarLoadingItem extends Model
{
    protected $table = 'pasgar_loading_items';

    protected $fillable = [
        'loading_id',
        'product_id',
        'sumber',
        'warehouse_id',
        'unit_conversion_id',
        'qty_diminta',
        'qty_disetujui',
        'qty_dikirim',
        'qty_terjual',
        'qty_sisa',
    ];

    public function loading()
    {
        return $this->belongsTo(PasgarLoading::class, 'loading_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function unitConversion()
    {
        return $this->belongsTo(ProductUnitConversion::class, 'unit_conversion_id');
    }

    public function getSumberLabelAttribute()
    {
        return match ($this->sumber) {
            'gudang' => 'Gudang',
            'grosir' => 'Grosir',
            default => '-',
        };
    }
}
