<?php

/* @var $factory Factory */

use App\Hook;
use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;

$factory->define(Hook::class, function (Faker $faker) {
    return [
        'url' => $faker->url,
        'cron' => "* * * * *",
        'threshold' => mt_rand(1, 5),
    ];
});
