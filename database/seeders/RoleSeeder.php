<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Enums\Role as RoleEnums;
use App\Enums\Permission as PermissionEnums;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionEnums::cases() as $permission) {
            Permission::firstOrCreate(['name' => $permission->value]);
        }

        foreach (RoleEnums::cases() as $role) {
            $createdRole = Role::firstOrCreate(['name' => $role->value]);

            $permissions = match ($role) {
                RoleEnums::Admin => PermissionEnums::all(),
                RoleEnums::User => PermissionEnums::forUser()
            };

            $createdRole->syncPermissions($permissions);
        }
    }
}
