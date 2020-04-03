<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Sponsor;

use Faker\Generator as Faker;

$factory->define(Sponsor::class, function (Faker $faker) {
    return [
        'hours' => $faker->randomFloat(24, 48, 72),
        'price' => $faker->randomFloat(3, 6, 10),

    ];
});
