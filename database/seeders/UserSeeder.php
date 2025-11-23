<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::firstOrCreate(
            ['email' => 'admin@tailoring.local'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
            ]
        );

        // Create test user
        User::firstOrCreate(
            ['email' => 'test@tailoring.local'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
