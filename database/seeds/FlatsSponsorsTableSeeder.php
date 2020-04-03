<?php

use Illuminate\Database\Seeder;
use App\Sponsor;
use App\Flat;


class FlatsSponsorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('flat_sponsor')->insert([
            [
                'flat_id' => 2,
                'sponsor_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
        DB::table('flat_sponsor')->insert([
            [
                'flat_id' => 4,
                'sponsor_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
        DB::table('flat_sponsor')->insert([
            [
                'flat_id' => 8,
                'sponsor_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
