<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderTravel>
 */
class OrderTravelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_travel_status_id' => 1,
            'name_applicant' => $this->faker->name,
            'destination' => $this->faker->city,
            'departure_date' => $this->faker->dateTimeThisYear,
            'return_date' => $this->faker->dateTimeThisYear,
            'user_id' => User::factory(),
        ];
    }
}
