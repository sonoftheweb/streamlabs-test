<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::query()->findOrFail(1);

        \App\Models\Subscriber::factory()
            ->count(rand(300, 350))
            ->create()
            ->each(function ($subscribers) use ($user) {
                $event = new Event([
                    'read' => (bool)rand(0, 1),
                    'created_at' => $subscribers->created_at,
                ]);
                $event->user()->associate($user);
                $subscribers->event()->save($event);
            });
    }
}
