<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create default roles
        $roles = [
            'admin' => 'Administrator with full access',
            'helper' => 'Community helper who provides assistance',
            'provider' => 'Service provider',
            'partner' => 'Community partner organization',
            'seeker' => 'Family seeking assistance',
        ];

        foreach ($roles as $roleName => $description) {
            Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
            $this->command->info("Role created: {$roleName}");
        }

        // Assign 'seeker' role to all users without a role
        $usersWithoutRole = User::doesntHave('roles')->get();
        
        foreach ($usersWithoutRole as $user) {
            $user->assignRole('seeker');
            $this->command->info("Assigned 'seeker' role to: {$user->name}");
        }

        $this->command->info('✓ All roles created and assigned successfully!');
    }
}