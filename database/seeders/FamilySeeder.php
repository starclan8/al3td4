<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Family;
use Illuminate\Support\Facades\Hash;

class FamilySeeder extends Seeder
{
    public function run(): void
    {
        $families = [
            [
                'family_name' => 'Martinez Family',
                'contact_email' => 'martinez.family@example.com',
                'contact_phone' => '555-0101',
                'password' => Hash::make('password'),
                'city' => 'San Francisco',
                'privacy_level' => 'first_name',
                'bio' => 'A young family navigating parenthood with two kids.',
                'is_demo' => true,
            ],
            [
                'family_name' => 'Johnson Family',
                'contact_email' => 'johnson.family@example.com',
                'contact_phone' => '555-0102',
                'password' => Hash::make('password'),
                'city' => 'Oakland',
                'privacy_level' => 'full_name',
                'bio' => 'Early career professional building a future.',
                'is_demo' => true,
            ],
        ];

        foreach ($families as $family) {
            Family::create($family);
            $this->command->info("Created family: {$family['family_name']}");
        }
    }
}