<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;

        $colorThemes = ['#C62828', '#1565C0', '#2E7D32', '#F57F17', '#424242'];

        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => strtolower("{$firstName}.{$lastName}@example.com"),
            'role' => $this->faker->randomElement(['admin', 'user']),
            'password' => bcrypt('password'),
            'color_theme' => $this->faker->randomElement($colorThemes),
            'created_at' => $this->faker->dateTimeBetween(
                now()->startOfMonth(),
                now()
            ),
        ];
    }
}
