<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\HealthCenter;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(HealthCenter::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'logo' => $faker->url,
        'address1' => $faker->address,
        'center_type' => 'clinic',
        'phone' => $faker->phoneNumber,
        'email' => $faker->email,
        'address2' => $faker->address,
        'state' => $faker->state,
        'city'  => $faker->city,
        'is_active' => true,
        'uuid'  => Str::uuid()->toString(),
        'vendor_id' => 1
    ];
});
