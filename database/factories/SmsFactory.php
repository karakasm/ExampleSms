<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sms>
 */
class SmsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => fake()->numberBetween(1,6),
            'number' => fake()->phoneNumber(),
            'message' => fake()->sentence,
            'send_time' => fake()->dateTimeBetween('-1 years','now','Europe/Istanbul'),
        ];
    }
}
