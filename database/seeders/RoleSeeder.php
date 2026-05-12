<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'admin', 'sales_manager', 'content_manager', 'readonly'] as $role) {
            Role::query()->firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $systemToolsPermission = Permission::query()->firstOrCreate(['name' => 'system_tools', 'guard_name' => 'web']);
        Role::query()->where('name', 'super_admin')->firstOrFail()->givePermissionTo($systemToolsPermission);

        User::query()->firstOrCreate(['email' => 'marcosborges@netlook.pt'], [
            'name' => 'Marcos Borges',
            'password' => 'Leonor(2026)',
        ])->assignRole('super_admin');
    }
}
