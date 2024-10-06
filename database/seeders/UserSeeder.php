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
            'email' => 'thomas.kanciano@example.com',
            'role' => 'admin',
            'password' => bcrypt('thomaskanciano'),
            'created_at' => Carbon::now()->setDate(2024, 9, 15)->setTime(12, 0)
        ]);

        User::create([
            'first_name' => 'Mark',
            'last_name' => 'Satechi',
            'email' => 'mark.satechi@example.com',
            'role' => 'user',
            'password' => bcrypt('marksatechi'),
            'created_at' => Carbon::now()->setDate(2024, 9, 20)->setTime(15, 30)
        ]);
    }
}
