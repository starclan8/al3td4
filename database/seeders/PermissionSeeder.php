<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions grouped by resource
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            'manage user roles',
            
            // Role & Permission Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign permissions',
            
            // Need Management
            'view needs',
            'create needs',
            'edit own needs',
            'edit all needs',
            'delete own needs',
            'delete all needs',
            'approve needs',
            
            // Helper Management
            'view helpers',
            'sign up as helper',
            'manage helpers',
            'approve helpers',
            
            // Family Management
            'view families',
            'create families',
            'edit own family',
            'edit all families',
            'delete families',
            
            // Calendar & Events
            'view calendar',
            'manage calendar',
            
            // Reports & Analytics
            'view reports',
            'export data',
            
            // System Settings
            'manage settings',
            'view logs',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
            $this->command->info("✓ Permission created: {$permission}");
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->command->info('✓ All permissions created and assigned successfully!');
    }

    /**
     * Assign permissions to each role
     */
    private function assignPermissionsToRoles(): void
    {
        // Admin - Full access to everything
        $admin = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());
        $this->command->info('✓ Admin role granted all permissions');

        // Helper - Can view needs, sign up to help, view calendar
        $helper = Role::findByName('helper');
        $helper->givePermissionTo([
            'view needs',
            'sign up as helper',
            'view calendar',
        ]);
        $this->command->info('✓ Helper role granted permissions');

        // Provider - Can manage services, view families in need
        $provider = Role::findByName('provider');
        $provider->givePermissionTo([
            'view needs',
            'view families',
            'sign up as helper',
            'view calendar',
        ]);
        $this->command->info('✓ Provider role granted permissions');

        // Partner - Can view reports, manage calendar, approve items
        $partner = Role::findByName('partner');
        $partner->givePermissionTo([
            'view needs',
            'view families',
            'view helpers',
            'approve needs',
            'approve helpers',
            'view calendar',
            'manage calendar',
            'view reports',
        ]);
        $this->command->info('✓ Partner role granted permissions');

        // Seeker - Can create and manage their own needs
        $seeker = Role::findByName('seeker');
        $seeker->givePermissionTo([
            'view needs',
            'create needs',
            'edit own needs',
            'delete own needs',
            'view calendar',
            'edit own family',
        ]);
        $this->command->info('✓ Seeker role granted permissions');
    }
}