<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RawMaterialOut extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(RawMaterialOutItem::class, 'raw_material_out_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateIssueNo()
    {
        $prefix = 'DC-OUT-' . date('Ymd') . '-';
        $latest = self::where('issue_no', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($latest) {
            $num = (int) str_replace($prefix, '', $latest->issue_no);
            return $prefix . str_pad($num + 1, 4, '0', STR_PAD_LEFT);
        }
        return $prefix . '0001';
    }
}
