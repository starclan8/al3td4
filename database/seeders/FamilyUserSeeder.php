<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FamilyUserSeeder extends Seeder
{
    public function run(): void
    {
        $families = [
            [
                'name' => 'Martinez Family',
                'email' => 'martinez.family@example.com',
                'password' => Hash::make('password'),
                'is_family' => true,
                'family_name' => 'Martinez Family',
                'contact_phone' => '555-0101',
                'city' => 'San Francisco',
                'privacy_level' => 'first_name',
                'bio' => 'A young family navigating parenthood with two kids. Currently seeking childcare support and willing to help other families with meal prep.',
                'is_demo' => true,
                'email_verified_at' => now(),
                'roles' => ['seeker', 'helper'], // Paying it forward!
            ],
            [
                'name' => 'Johnson Family',
                'email' => 'johnson.family@example.com',
                'password' => Hash::make('password'),
                'is_family' => true,
                'family_name' => 'Johnson Family',
                'contact_phone' => '555-0102',
                'city' => 'Oakland',
                'privacy_level' => 'full_name',
                'bio' => 'Single parent household working full-time. Need occasional help with after-school pickup.',
                'is_demo' => true,
                'email_verified_at' => now(),
                'roles' => ['seeker'],
            ],
            [
                'name' => 'Chen Family',
                'email' => 'chen.family@example.com',
                'password' => Hash::make('password'),
                'is_family' => true,
                'family_name' => 'Chen Family',
                'contact_phone' => '555-0103',
                'city' => 'Berkeley',
                'privacy_level' => 'anonymous',
                'bio' => 'Recently moved to the area. Looking for help settling in and community connections.',
                'is_demo' => true,
                'email_verified_at' => now(),
                'roles' => ['seeker'],
            ],
            [
                'name' => 'Williams Family',
                'email' => 'williams.family@example.com',
                'password' => Hash::make('password'),
                'is_family' => true,
                'family_name' => 'Williams Family',
                'contact_phone' => '555-0104',
                'city' => 'San Francisco',
                'privacy_level' => 'first_name',
                'bio' => 'Empty nesters who love helping families in our community. Have flexible schedules and happy to assist!',
                'is_demo' => true,
                'email_verified_at' => now(),
                'roles' => ['helper'], // Pure helper family
            ],
        ];

        foreach ($families as $familyData) {
            $roles = $familyData['roles'];
            unset($familyData['roles']);

            $family = User::create($familyData);
            $family->assignRole($roles);

            $rolesList = implode(', ', $roles);
            $this->command->info("✓ Created family: {$family->name} ({$family->email}) - Roles: {$rolesList}");
        }

        $this->command->info('✓ All demo families created successfully!');
        $this->command->info('Default password for all families: password');
    }
}