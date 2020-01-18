<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Vendor;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'server_url' => $faker->url,
        'api_key' => Str::random(10),
        'api_secret' => Str::random(24)
    ];
});
