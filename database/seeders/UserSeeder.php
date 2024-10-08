<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();

        User::create([
            'first_name' => 'Thomas',
            'last_name' => 'Kanciano',
            'email' => 'thomas.admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('thomas'),
            'created_at' => Carbon::now()->setDate(2024, 9, 15)->setTime(12, 0)
        ]);

        User::create([
            'first_name' => 'George',
            'last_name' => 'Dutch',
            'email' => 'george.user@example.com',
            'role' => 'user',
            'password' => bcrypt('george'),
            'created_at' => Carbon::now()->setDate(2024, 9, 20)->setTime(15, 30)
        ]);
    }
}
