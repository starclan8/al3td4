<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Need;
use App\Models\User;
use App\Models\NeedType;

class NeedSeeder extends Seeder
{
    public function run(): void
    {
        $martinez = User::where('email', 'martinez.family@example.com')->first();
        $johnson = User::where('email', 'johnson.family@example.com')->first();
        $chen = User::where('email', 'chen.family@example.com')->first();

        $childcare = NeedType::where('slug', 'childcare')->first();
        $meals = NeedType::where('slug', 'meals')->first();
        $transportation = NeedType::where('slug', 'transportation')->first();
        $household = NeedType::where('slug', 'household')->first();

        $needs = [
            [
                'user' => $martinez,
                'title' => 'After-school pickup help needed',
                'description' => 'Need someone to pick up our 7-year-old from school on Tuesdays and Thursdays at 3pm. School is 10 minutes away.',
                'status' => 'active',
                'needed_by' => now()->addWeek(),
                'location' => 'San Francisco',
                'urgency' => 'medium',
                'helper_slots' => 1,
                'is_recurring' => true,
                'recurrence_pattern' => 'weekly',
                'types' => [$childcare, $transportation],
            ],
            [
                'user' => $johnson,
                'title' => 'Meal prep assistance this weekend',
                'description' => 'Working double shifts this week. Would love help preparing a few meals for the family to eat throughout the week.',
                'status' => 'active',
                'needed_by' => now()->addDays(3),
                'location' => 'Oakland',
                'urgency' => 'high',
                'helper_slots' => 2,
                'types' => [$meals],
            ],
            [
                'user' => $chen,
                'title' => 'Help unpacking after move',
                'description' => 'Just moved to the area and could use help unpacking boxes and organizing the house.',
                'status' => 'active',
                'needed_by' => now()->addDays(5),
                'location' => 'Berkeley',
                'urgency' => 'low',
                'helper_slots' => 3,
                'types' => [$household],
            ],
            [
                'user' => $martinez,
                'title' => 'Yard work help needed',
                'description' => 'Our yard needs some TLC - weeding, mowing, and general cleanup. Happy to provide supplies!',
                'status' => 'pending',
                'needed_by' => now()->addWeeks(2),
                'location' => 'San Francisco',
                'urgency' => 'low',
                'helper_slots' => 2,
                'types' => [$household],
            ],
        ];

        foreach ($needs as $needData) {
            $user = $needData['user'];
            $types = $needData['types'];
            unset($needData['user'], $needData['types']);

            $need = $user->needs()->create($needData);
            $need->needTypes()->attach($types);

            $this->command->info("✓ Created need: {$need->title} for {$user->name}");
        }

        $this->command->info('✓ All demo needs created successfully!');
    }
}