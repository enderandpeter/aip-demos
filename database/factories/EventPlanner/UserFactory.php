<?php

namespace Database\Factories\EventPlanner;

use App\Models\EventPlanner\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'password' => bcrypt(Str::random(255)),
            'remember_token' => Str::random(10),
        ];
    }

    public function clearpass(){
        return $this->state(function (array $attributes) {
            $clearPass = $this->faker->password(6, 255);
            return [
                'clearpass' => $clearPass,
                'password' => bcrypt($clearPass),
            ];
        });
    }
}
