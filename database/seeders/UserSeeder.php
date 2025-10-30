<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Publication;
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

        // Create a publication for the writer
        $publication = Publication::create([
            'user_id' => $writer->id,
            'name' => 'Tech Weekly',
            'slug' => 'tech-weekly',
            'description' => 'Weekly digest of technology news',
            'substack_url' => 'https://techweekly.substack.com',
            'verified' => true,
            'subscriber_count' => 1250,
        ]);
    }
}


