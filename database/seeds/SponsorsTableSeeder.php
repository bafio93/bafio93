<?php

use Illuminate\Database\Seeder;
use App\Sponsor;
class SponsorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
         // Questa impostazione crea a casa sponsor faker
         // factory(App\Sponsor::class, 5)->create();
         // factory(App\Flat::class)->create()->each(function($a) {
         //   $a->sponsors()->attach(App\Sponsor::all()->random(1));
         // });
         // questa imopstazione crea sponsor statici
         $sponsors = config('sponsor.sponsor_db');
         foreach ($sponsors as $sponsor) {
            $new_sponsor = new Sponsor();

            $new_sponsor->fill($sponsor);

            $new_sponsor->save();
        }

     }
}
