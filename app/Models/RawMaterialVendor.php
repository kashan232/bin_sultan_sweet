<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RawMaterialVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function purchases()
    {
        return $this->hasMany(RawMaterialPurchase::class, 'vendor_id');
    }

    public function ledgers()
    {
        return $this->hasMany(RawMaterialVendorLedger::class, 'vendor_id')->orderBy('date', 'asc')->orderBy('id', 'asc');
    }

    public function latestLedger()
    {
        return $this->hasOne(RawMaterialVendorLedger::class, 'vendor_id')->latestOfMany();
    }
}
