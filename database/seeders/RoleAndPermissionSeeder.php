<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'task-create',
            'task-view-own',
            'task-view-all',
            'task-update-own',
            'task-update-all',
            'task-delete-own',
            'task-delete-all',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        $viewerRole = Role::create(['name' => 'viewer']);
        $viewerRole->givePermissionTo([
            'task-view-own',
        ]);

        $editorRole = Role::create(['name' => 'editor']);
        $editorRole->givePermissionTo([
            'task-create',
            'task-view-own',
            'task-update-own',
            'task-delete-own',
        ]);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'task-create',
            'task-view-own',
            'task-view-all',
            'task-update-all',
            'task-delete-all',
        ]);
    }
}