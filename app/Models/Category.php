<?php

namespace App\Models;

use App\Models\Subcategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    function subcategory(){
        return $this->hasMany(Subcategory::class);
    }

    public function scopeRestricted($query, $user = null)
    {
        $user = $user ?: auth()->user();
        if (!$user || !$user->hasProductRestriction()) {
            return $query;
        }

        $categoryIds = $user->getRestrictedCategoryIds();
        
        $productCategoryIds = \App\Models\Product::whereIn('id', $user->getRestrictedProductIds())
            ->pluck('category_id')
            ->filter()
            ->unique()
            ->toArray();

        $allAllowedCategoryIds = array_unique(array_merge($categoryIds, $productCategoryIds));

        if (empty($allAllowedCategoryIds)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn('id', $allAllowedCategoryIds);
    }
}

