<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Employee;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'salary' =>$faker->numberBetween($min = 1000000,$max=5000000),
        'age'=>$faker->numberBetween($min = 0,$max=100),
        'profile_image'=>"",
    ];
});
