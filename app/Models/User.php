<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
// use Spatie\Permission\Models\Role;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//    public function roles()
//     {
//         return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
//                     ->where('model_type', User::class);
//     }

    /**
     * Check if user is restricted to specific products/categories.
     */
    public function hasProductRestriction(): bool
    {
        if ($this->hasRole('super-admin')) {
            return false;
        }
        try {
            return $this->hasPermissionTo('specific product');
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get restricted category IDs for the user.
     */
    public function getRestrictedCategoryIds(): array
    {
        if (!$this->hasProductRestriction()) {
            return [];
        }

        $roleIds = $this->roles()->pluck('id')->toArray();
        return \DB::table('role_categories')
            ->whereIn('role_id', $roleIds)
            ->pluck('category_id')
            ->toArray();
    }

    /**
     * Get restricted product IDs for the user.
     */
    public function getRestrictedProductIds(): array
    {
        if (!$this->hasProductRestriction()) {
            return [];
        }

        $roleIds = $this->roles()->pluck('id')->toArray();
        return \DB::table('role_products')
            ->whereIn('role_id', $roleIds)
            ->pluck('product_id')
            ->toArray();
    }
}

