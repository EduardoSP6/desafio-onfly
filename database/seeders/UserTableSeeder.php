<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@onfly.com',
            'is_admin' => true,
        ]);

        User::factory()->create([
            'name' => 'User 1',
            'email' => 'user1@onfly.com',
        ]);

        User::factory()->create([
            'name' => 'User 2',
            'email' => 'user2@onfly.com',
        ]);
    }
}
