<?php

use Illuminate\Database\Seeder;

class FlatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // creo per ogni cliente (in questo caso due) un collegamento con un appartamento
        factory(App\User::class, 10)->create()->each(function ($user) {
            // Seed the relation with 1 flats
            $flats = factory(App\Flat::class, 1)->make();

            $user->flats()->saveMany($flats);

        });


    }
}
