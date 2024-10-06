<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    protected $model = Sale::class;

    public function definition(): array
    {
        $product = Product::all()->random();
        $quantity = $this->faker->numberBetween(1, 15);
        $month = $this->faker->numberBetween(1, now()->month);
        $day = $this->faker->numberBetween(1, Carbon::create(now()->year, $month)->daysInMonth);
        $hour = $this->faker->numberBetween(0, 23);
        $minute = $this->faker->numberBetween(0, 59);

        return [
            'product_id' => $product->id,
            'quantity' => $quantity,
            'total_price' => $quantity * $product->price,
            'created_at' => Carbon::create(now()->year, $month, $day, $hour, $minute)
        ];
    }
}
