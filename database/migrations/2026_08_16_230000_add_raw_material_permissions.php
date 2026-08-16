<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'Raw Materials',
            'Raw Material Stock Report',
            'Raw Material Purchase',
            'Raw Material Out',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Assign all permissions to super-admin & admin roles if they exist
        $allPermissions = Permission::all();

        $superAdminRole = Role::where('name', 'super-admin')->first();
        if ($superAdminRole) {
            $superAdminRole->syncPermissions($allPermissions);
        }

        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            foreach ($permissions as $permissionName) {
                if (!$adminRole->hasPermissionTo($permissionName)) {
                    $adminRole->givePermissionTo($permissionName);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
