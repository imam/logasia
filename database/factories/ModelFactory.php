<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Date::class, function(Faker\Generator $faker){
    return [

    ];
});

$factory->define(App\SemiTrailerTruck::class, function(Faker\Generator $faker){
    return [
        'vehicles_available' =>2,
        'price' => 60000
    ];
});

$factory->define(App\SwapBodyTruck::class, function(Faker\Generator $faker){
    return [
        'vehicles_available' =>2,
        'price' => 60000
    ];
});

$factory->define(App\PupTrailer::class, function(Faker\Generator $faker){
    return [
        'vehicles_available' =>2,
        'price' => 7000
    ];
});
