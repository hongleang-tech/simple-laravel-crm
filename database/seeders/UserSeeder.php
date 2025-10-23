<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'admin@example.com',
        ]);

        $user = User::factory()->create([
            'first_name' => 'Matt',
            'last_name' => 'Brown',
            'email' => 'user@example.com',
        ]);

        User::factory()->count(10)->create();
    }
}
