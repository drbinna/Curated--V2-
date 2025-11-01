<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Tech news and updates', 'icon' => '💻', 'color' => '#3B82F6'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Business and entrepreneurship', 'icon' => '💼', 'color' => '#10B981'],
            ['name' => 'Science', 'slug' => 'science', 'description' => 'Science and research', 'icon' => '🔬', 'color' => '#8B5CF6'],
            ['name' => 'Arts & Culture', 'slug' => 'arts-culture', 'description' => 'Arts and cultural content', 'icon' => '🎨', 'color' => '#EC4899'],
            ['name' => 'Politics', 'slug' => 'politics', 'description' => 'Political news and analysis', 'icon' => '🏛️', 'color' => '#EF4444'],
            ['name' => 'Health', 'slug' => 'health', 'description' => 'Health and wellness', 'icon' => '🏥', 'color' => '#14B8A6'],
            ['name' => 'Finance', 'slug' => 'finance', 'description' => 'Financial news and advice', 'icon' => '💰', 'color' => '#F59E0B'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports news and updates', 'icon' => '⚽', 'color' => '#06B6D4'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}





