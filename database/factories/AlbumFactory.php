<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Album;
use Faker\Generator as Faker;

$factory->define(Album::class, function (Faker $faker) {
    return [
        'name'=>$faker->name,
        'year'=>$faker->randomNumber(4),
        'ntracks'=>$faker->randomNumber(3),
        'length'=>$faker->randomNumber(4)

    ];
});
