<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AlbumArtista;
use Faker\Generator as Faker;

$factory->define(AlbumArtista::class, function (Faker $faker) {
    return [
        'listened'=>$faker->boolean(50)
    ];
});
