<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialOutItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function out()
    {
        return $this->belongsTo(RawMaterialOut::class, 'raw_material_out_id');
    }

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class, 'raw_material_id');
    }
}
