<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Appointment;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Appointment::class, function (Faker $faker) {
    $oneWeek = Carbon::today()->addWeek();
    return [
        'uuid' => Str::uuid()->toString(),
        'description' => $faker->text,
        'user_uuid' => '',
        'status' => 'pending',
        'reason' => $faker->text,
        'date' => $oneWeek->toDateString(),
        'time' => $oneWeek->toTimeString(),
        'location' => $faker->address,
        'center_uuid' => ''
    ];
});
