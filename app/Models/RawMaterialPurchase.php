<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialPurchase extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(RawMaterialVendor::class, 'vendor_id');
    }

    public function items()
    {
        return $this->hasMany(RawMaterialPurchaseItem::class, 'raw_material_purchase_id');
    }

    public static function generatePurchaseNo()
    {
        $last = self::latest('id')->first();
        $next = $last ? ($last->id + 1) : 1;
        return 'RMP-' . date('Ymd') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
