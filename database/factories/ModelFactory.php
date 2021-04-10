<?php

namespace Database\Factories;

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

$factory->define(App\EventPlanner\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\EventPlanner\CalendarEvent::class, function (Faker\Generator $faker) {
	$start_date = $faker->dateTimeThisYear;
	$end_date = ( Carbon\Carbon::instance( $start_date ) )->addHour();
	
	return [
			'start_date' => $start_date,
			'end_date' => $end_date,
			'type' => $faker->word,
			'name' => $faker->words(3, true),
			'host' => $faker->name,
			'guest_list' => $faker->sentence,
			'location' => $faker->address,
			'guest_message' => $faker->realText(),
			'user_id' => function(){
				return factory( App\EventPlanner\User::class )->create()->id;
			}
	];
});