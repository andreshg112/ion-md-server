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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
    'name' => $faker->name,
    'email' => $faker->safeEmail,
    'password' => bcrypt(str_random(10)),
    'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\Materia::class, function (Faker\Generator $faker) {
    return [
    'codigo' => strtoupper($faker->bothify('??###')),
    'nombre' => $faker->catchPhrase,
    'creditos' => $faker->numberBetween($min = 1, $max = 20),
    'programa_id' => $faker->numberBetween($min = 1, $max = 18)
    ];
});

$factory->define(App\Models\Tutor::class, function (Faker\Generator $faker) {
    return [
    'primer_nombre' => $faker->firstName,
    'segundo_nombre' => $faker->firstName,
    'primer_apellido' => $faker->lastName,
    'segundo_apellido' => $faker->lastName
    ];
});