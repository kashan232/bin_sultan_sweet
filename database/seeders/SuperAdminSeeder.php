<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin User بنائیں
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@admin.com',
                'password' => Hash::make('12345678'),
            ]
        );

        // super-admin role بنائیں (اگر نہیں ہے)
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);

        // تمام permissions assign کریں super-admin role کو
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        // Super Admin کو role assign کریں
        $superAdmin->syncRoles([$superAdminRole]);

        $this->command->info('✅ Super Admin بن گیا: admin@admin.com | Password: 12345678');
    }
}
