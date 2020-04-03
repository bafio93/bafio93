<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Message;

use Faker\Generator as Faker;

$factory->define(Message::class, function (Faker $faker) {
    return [
        'msg_email' => $faker->realText($maxNbChars = 50, $indexSize = 1),
        'text_msg' => $faker->realText($maxNbChars = 200, $indexSize = 2) ,

    ];
});
