<?php

namespace Database\Factories\EventPlanner;

use App\Models\EventPlanner\CalendarEvent;
use App\Models\EventPlanner\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CalendarEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CalendarEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_date = $this->faker->dateTimeThisYear;
        $end_date = ( Carbon::instance( $start_date ) )->addHour();

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'type' => $this->faker->word,
            'name' => $this->faker->words(3, true),
            'host' => $this->faker->name,
            'guest_list' => $this->faker->sentence,
            'location' => $this->faker->address,
            'guest_message' => $this->faker->realText(),
            'user_id' => function(){
                return User::factory()->create()->id;
            }
        ];
    }
}
