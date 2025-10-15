<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // bersihkan cache permission Spatie (aman)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // buat permission (pakai guard 'api') - cek dulu apakah sudah ada
        $permissions = [
            'view users',
            'edit users',
            'delete users',
            'create users',
            'view reservations',
            'create reservations',
            'edit reservations',
            'delete reservations',
            'approve reservations',
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'api']
            );
        }

        // buat role dan beri permission - cek dulu apakah sudah ada
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'api']);
        $admin->syncPermissions([
            'view users',
            'edit users',
            'create users',
            'delete users',
            'view reservations',
            'create reservations',
            'edit reservations',
            'delete reservations',
            'approve reservations',
            'view rooms',
            'create rooms',
            'edit rooms',
            'delete rooms',
            'view schedules',
            'create schedules',
            'edit schedules',
            'delete schedules'
        ]);

        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'api']);
        $staff->syncPermissions([
            'view users',
            'view reservations',
            'create reservations',
            'edit reservations',
            'approve reservations',
            'view rooms',
            'create rooms',
            'edit rooms',
            'view users',
            'view reservations',
            'approve reservations',
            'view rooms'
        ]);

        $student = Role::firstOrCreate(['name' => 'student', 'guard_name' => 'api']);
        $student->syncPermissions([
            'view reservations',
            'create reservations',
            'edit reservations',
            'view reservations',
            'create reservations',
            'view rooms'
        ]);
    }
}
