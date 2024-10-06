<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Message>
 */
class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'user_id' => User::all()->random()->id,
            'created_at' => $this->faker->dateTimeBetween(date('Y-m-01'), date('Y-m-d'))
        ];
    }
}
