<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterial extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function purchaseItems()
    {
        return $this->hasMany(RawMaterialPurchaseItem::class, 'raw_material_id');
    }
}
