<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchSaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::query()->findOrFail(1);

        \App\Models\MerchSale::factory()
            ->count(rand(300, 400))
            ->create()
            ->each(function ($merchSale) use ($user) {
                $event = new Event([
                    'read' => (bool)rand(0, 1),
                    'created_at' => $merchSale->created_at,
                ]);
                $event->user()->associate($user);
                $merchSale->event()->save($event);
            });
    }
}
