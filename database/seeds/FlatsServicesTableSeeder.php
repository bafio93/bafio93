<?php

use Illuminate\Database\Seeder;
use App\Service;
use App\Flat;


class FlatsServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('flat_service')->insert([
            [
                'flat_id' => 1,
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 1,
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 2,
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 2,
                'service_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 5,
                'service_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 3,
                'service_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 5,
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 7,
                'service_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 8,
                'service_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        DB::table('flat_service')->insert([
            [
                'flat_id' => 9,
                'service_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
