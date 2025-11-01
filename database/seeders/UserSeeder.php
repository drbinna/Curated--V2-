<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create test users
        $reader = User::create([
            'name' => 'John Reader',
            'email' => 'reader@example.com',
            'password' => Hash::make('password'),
            'username' => 'johndoe',
            'user_type' => 'reader',
            'bio' => 'I love reading newsletters!',
        ]);

        $writer = User::create([
            'name' => 'Jane Writer',
            'email' => 'writer@example.com',
            'password' => Hash::make('password'),
            'username' => 'janewriter',
            'user_type' => 'writer',
            'bio' => 'I write about technology and innovation.',
        ]);
    }
}





