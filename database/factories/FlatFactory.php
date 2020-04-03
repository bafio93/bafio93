<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Flat;

use Faker\Generator as Faker;

$factory->define(Flat::class, function (Faker $faker) {
    return [
        'title' =>('Appartamento in ').$faker->randomElement(['Roma','Parigi','Madrid','Barcellona','Berlino','Milano','Napoli','Torino','Venezia','New York','Los Angeles','Amsterdam']),
        'room_qty' => $faker->numberBetween($min = 1, $max = 5) ,
        'bed_qty' => $faker->numberBetween($min = 1, $max = 10) ,
        'bath_qty' => $faker->numberBetween($min = 1, $max = 5) ,
        'sq_meters' => $faker->numberBetween($min = 10, $max = 500) ,
        'address' => $faker->streetAddress,
        'lat' => $faker->latitude($min = -90, $max = 90),
        'lon' => $faker->longitude($min = -180, $max = 180),
        'img_uri' => '',
        'active' => '1',
    ];
});
