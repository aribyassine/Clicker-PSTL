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
use Illuminate\Support\Facades\Hash;

$factory->define(App\User::class, function (Faker\Generator $faker) {

    return [
        'code' => $faker->numberBetween(3500000,3700000),
        'nom' => $faker->lastName,
        'prenom' => $faker->firstName,
        'password' => Hash::make('secret'),
    ];
});
