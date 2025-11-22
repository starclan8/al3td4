<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class PersonaUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Create demo users based on personas
        $personas = [
            // Admin User
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => 'password',
                'role' => 'admin',
            ],
            
            // Persona 1: Tech-Savvy Parent (Sarah, 34)
            [
                'name' => 'Sarah Martinez',
                'email' => 'sarah.martinez@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            
            // Persona 2: Early Career Professional (Marcus, 28)
            [
                'name' => 'Marcus Johnson',
                'email' => 'marcus.johnson@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            
            // Persona 3: Small Business Owner (Linda, 45)
            [
                'name' => 'Linda Chen',
                'email' => 'linda.chen@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            
            // Persona 4: Recent College Graduate (Alex, 23)
            [
                'name' => 'Alex Thompson',
                'email' => 'alex.thompson@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            
            // Persona 5: Retiree (Robert, 68)
            [
                'name' => 'Robert Williams',
                'email' => 'robert.williams@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            
            // Additional diverse users
            [
                'name' => 'Priya Patel',
                'email' => 'priya.patel@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'James Anderson',
                'email' => 'james.anderson@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
            [
                'name' => 'David Kim',
                'email' => 'david.kim@example.com',
                'password' => 'password',
                'role' => 'user',
            ],
        ];

        foreach ($personas as $persona) {
            $user = User::firstOrCreate(
                ['email' => $persona['email']],
                [
                    'name' => $persona['name'],
                    'password' => Hash::make($persona['password']),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role
            if (!$user->hasRole($persona['role'])) {
                $user->assignRole($persona['role']);
            }

            $this->command->info("Created user: {$persona['name']} ({$persona['email']})");
        }

        $this->command->info('✓ All persona-based demo users created successfully!');
        $this->command->info('Default password for all users: password');
    }
}