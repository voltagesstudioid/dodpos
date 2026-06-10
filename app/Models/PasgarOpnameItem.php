<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasgarOpnameItem extends Model
{
    protected $table = 'pasgar_opname_items';

    protected $fillable = [
        'opname_id',
        'loading_item_id',
        'product_id',
        'qty_sisa_sistem',
        'qty_fisik',
        'qty_selisih',
        'warehouse_id',
    ];

    protected $casts = [
        'qty_sisa_sistem' => 'integer',
        'qty_fisik' => 'integer',
        'qty_selisih' => 'integer',
    ];

    // --- Relationships ---

    public function opname()
    {
        return $this->belongsTo(PasgarOpname::class, 'opname_id');
    }

    public function loadingItem()
    {
        return $this->belongsTo(PasgarLoadingItem::class, 'loading_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    // --- Accessors ---

    public function getSelisihLabelAttribute(): string
    {
        if ($this->qty_selisih === 0) {
            return 'Pas';
        }
        return $this->qty_selisih > 0 ? 'Lebih' : 'Kurang';
    }

    public function getSelisihColorAttribute(): string
    {
        if ($this->qty_selisih === 0) {
            return 'emerald';
        }
        return $this->qty_selisih > 0 ? 'amber' : 'red';
    }
}
