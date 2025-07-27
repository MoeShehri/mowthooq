<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Transaction permissions
            'view-transactions',
            'create-transactions',
            'edit-transactions',
            'delete-transactions',
            'approve-transactions',
            'reject-transactions',
            
            // User management permissions
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Report permissions
            'view-reports',
            'export-reports',
            
            // System permissions
            'manage-settings',
            'view-logs',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // أفراد (Individuals) - Basic users
        $individualRole = Role::create(['name' => 'individual']);
        $individualRole->givePermissionTo([
            'view-transactions',
            'create-transactions',
            'edit-transactions',
        ]);

        // مكاتب هندسية (Engineering Offices) - Advanced submission tools
        $officeRole = Role::create(['name' => 'office']);
        $officeRole->givePermissionTo([
            'view-transactions',
            'create-transactions',
            'edit-transactions',
            'view-reports',
            'export-reports',
        ]);

        // مطورين عقاريين (Real Estate Developers) - Analytics and bulk operations
        $developerRole = Role::create(['name' => 'developer']);
        $developerRole->givePermissionTo([
            'view-transactions',
            'create-transactions',
            'edit-transactions',
            'delete-transactions',
            'view-reports',
            'export-reports',
            'view-users',
        ]);

        // مدير (Admin) - Full system control
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Created roles: individual, office, developer, admin');
        $this->command->info('Created ' . count($permissions) . ' permissions');
    }
}
