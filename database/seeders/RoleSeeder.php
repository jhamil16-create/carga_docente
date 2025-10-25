<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Base permissions
        $permissions = [
            'manage faculties',
            'manage subjects',
            'manage groups',
            'manage classrooms',
            'manage schedules',
            'record attendance',
            'view reports',
            'import users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Roles
        $roles = [
            'admin' => $permissions,
            'coordinator' => [
                'manage faculties', 'manage subjects', 'manage groups', 'manage classrooms', 'manage schedules', 'view reports', 'import users'
            ],
            'instructor' => ['record attendance', 'view reports'],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}