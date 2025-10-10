<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        
        // Buat Permissions
        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'edit users']);
        Permission::create(['name' => 'delete users']);
        Permission::create(['name' => 'view own profile']);

        // Buat Roles dan berikan permissions
        $studentRole = Role::create(['name' => 'Student']);
        $studentRole->givePermissionTo('view own profile');

        $staffRole = Role::create(['name' => 'Staff']);
        $staffRole->givePermissionTo('view users');
        $staffRole->givePermissionTo('view own profile');

        $adminRole = Role::create(['name' => 'Admin']);
        // Admin mendapatkan semua permission secara implisit atau bisa didefinisikan satu per satu
        // Cara mudah: berikan semua permission yang ada
        $adminRole->givePermissionTo(Permission::all());
    }
}
