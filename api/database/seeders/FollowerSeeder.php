<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FollowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::query()->findOrFail(1);

        \App\Models\Follower::factory()
            ->count(436)
            ->create()
            ->each(function ($follower) use ($user) {
                $event = new Event([
                    'read' => (bool)rand(0, 1),
                    'created_at' => $follower->created_at,
                ]);
                $event->user()->associate($user);
                $follower->event()->save($event);
            });
    }
}
