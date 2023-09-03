<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::query()->findOrFail(1);

        \App\Models\Donation::factory()
            ->count(rand(300, 500))
            ->create()
            ->each(function ($donation) use ($user) {
                $event = new Event([
                    'read' => (bool)rand(0, 1),
                    'created_at' => $donation->created_at,
                ]);
                $event->user()->associate($user);
                $donation->event()->save($event);
            });
    }
}
