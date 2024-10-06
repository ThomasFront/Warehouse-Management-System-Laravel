<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Electronics
            ['name' => 'Laptop', 'description' => 'A high-performance laptop', 'category_id' => 1, 'stock' => 10, 'price' => 549.99],
            ['name' => 'Smartphone', 'description' => 'A latest model smartphone', 'category_id' => 1, 'stock' => 12, 'price' => 349.99],
            ['name' => 'Tablet', 'description' => 'A powerful tablet', 'category_id' => 1, 'stock' => 15, 'price' => 499.99],
            ['name' => 'Headphones', 'description' => 'Noise-cancelling headphones', 'category_id' => 1, 'stock' => 30, 'price' => 199.99],
            ['name' => 'Camera', 'description' => 'A DSLR camera', 'category_id' => 1, 'stock' => 5, 'price' => 399.99],

            // Books
            ['name' => 'Fiction Novel', 'description' => 'An engaging fiction novel', 'category_id' => 2, 'stock' => 25, 'price' => 14.99],
            ['name' => 'Non-Fiction Book', 'description' => 'A thought-provoking non-fiction book', 'category_id' => 2, 'stock' => 20, 'price' => 19.99],
            ['name' => 'Cookbook', 'description' => 'A collection of delicious recipes', 'category_id' => 2, 'stock' => 15, 'price' => 24.99],
            ['name' => 'Biography', 'description' => 'A biography of a famous person', 'category_id' => 2, 'stock' => 10, 'price' => 12.99],
            ['name' => 'Science Fiction Novel', 'description' => 'An intriguing science fiction story', 'category_id' => 2, 'stock' => 18, 'price' => 16.99],

            // Games
            ['name' => 'Board Game', 'description' => 'A fun family board game', 'category_id' => 3, 'stock' => 12, 'price' => 29.99],
            ['name' => 'Card Game', 'description' => 'A strategic card game', 'category_id' => 3, 'stock' => 25, 'price' => 19.99],
            ['name' => 'Video Game', 'description' => 'An exciting action video game', 'category_id' => 3, 'stock' => 30, 'price' => 59.99],
            ['name' => 'Puzzle', 'description' => 'A challenging jigsaw puzzle', 'category_id' => 3, 'stock' => 10, 'price' => 9.99],
            ['name' => 'Action Figure', 'description' => 'A collectible action figure', 'category_id' => 3, 'stock' => 40, 'price' => 14.99],

            // Toys
            ['name' => 'Doll', 'description' => 'A beautiful doll', 'category_id' => 4, 'stock' => 50, 'price' => 29.99],
            ['name' => 'Building Blocks', 'description' => 'A set of building blocks', 'category_id' => 4, 'stock' => 20, 'price' => 19.99],
            ['name' => 'Action Figure', 'description' => 'A popular action figure', 'category_id' => 4, 'stock' => 15, 'price' => 14.99],
            ['name' => 'Remote Control Car', 'description' => 'A fun remote control car', 'category_id' => 4, 'stock' => 8, 'price' => 39.99],
            ['name' => 'Educational Toy', 'description' => 'A toy that helps kids learn', 'category_id' => 4, 'stock' => 25, 'price' => 24.99],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
