<?php

use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Flat::class)->create()->each(function ($flat) {
            // Seed the relation with 1 flats
            $messages = factory(App\Message::class, 1)->make();
            $flat->messages()->saveMany($messages);
        });
    }
}
