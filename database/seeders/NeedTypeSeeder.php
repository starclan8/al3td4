<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NeedType;

class NeedTypeSeeder extends Seeder
{
    public function run(): void
    {
        $needTypes = [
            [
                'name' => 'Childcare',
                'slug' => 'childcare',
                'description' => 'Help with watching children, pickup/dropoff, or supervision',
                'icon' => 'fa-child',
                'color' => '#FF6B6B',
                'order' => 1,
            ],
            [
                'name' => 'Meals',
                'slug' => 'meals',
                'description' => 'Meal preparation, delivery, or groceries',
                'icon' => 'fa-utensils',
                'color' => '#4ECDC4',
                'order' => 2,
            ],
            [
                'name' => 'Transportation',
                'slug' => 'transportation',
                'description' => 'Rides to appointments, school, or errands',
                'icon' => 'fa-car',
                'color' => '#45B7D1',
                'order' => 3,
            ],
            [
                'name' => 'Household',
                'slug' => 'household',
                'description' => 'Cleaning, repairs, yard work, or organization',
                'icon' => 'fa-home',
                'color' => '#96CEB4',
                'order' => 4,
            ],
            [
                'name' => 'Errands',
                'slug' => 'errands',
                'description' => 'Shopping, pickup/delivery, or other tasks',
                'icon' => 'fa-shopping-bag',
                'color' => '#FFEAA7',
                'order' => 5,
            ],
            [
                'name' => 'Emotional Support',
                'slug' => 'emotional-support',
                'description' => 'Companionship, conversation, or check-ins',
                'icon' => 'fa-heart',
                'color' => '#DDA15E',
                'order' => 6,
            ],
            [
                'name' => 'Professional Services',
                'slug' => 'professional-services',
                'description' => 'Legal, financial, medical, or other professional help',
                'icon' => 'fa-briefcase',
                'color' => '#BC6C25',
                'order' => 7,
            ],
            [
                'name' => 'Moving/Furniture',
                'slug' => 'moving-furniture',
                'description' => 'Help with moving, lifting, or furniture assembly',
                'icon' => 'fa-dolly',
                'color' => '#606C38',
                'order' => 8,
            ],
        ];

        foreach ($needTypes as $type) {
            NeedType::create($type);
            $this->command->info("✓ Created need type: {$type['name']}");
        }

        $this->command->info('✓ All need types created successfully!');
    }
}