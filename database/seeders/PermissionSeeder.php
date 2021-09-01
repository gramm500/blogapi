<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
        $userPermission = Permission::create(['name' => 'write']);
        $adminPermission = Permission::create(['name' => 'write and delete']);
        $userRole = Role::create(['name' => 'user'])->givePermissionTo($userPermission);
        $adminRole = Role::create(['name' => 'admin'])->givePermissionTo($adminPermission);
    }
}
