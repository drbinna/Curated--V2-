<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'slug' => 'technology',
                'description' => 'Latest tech news, product reviews, software development, and innovation',
                'icon' => '💻',
                'color' => '#3B82F6'
            ],
            [
                'name' => 'Business',
                'slug' => 'business',
                'description' => 'Business strategy, entrepreneurship, startup news, and industry insights',
                'icon' => '💼',
                'color' => '#10B981'
            ],
            [
                'name' => 'Science',
                'slug' => 'science',
                'description' => 'Scientific research, discoveries, and analysis across all fields',
                'icon' => '🔬',
                'color' => '#8B5CF6'
            ],
            [
                'name' => 'Arts & Culture',
                'slug' => 'arts-culture',
                'description' => 'Visual arts, literature, music, film, and cultural commentary',
                'icon' => '🎨',
                'color' => '#EC4899'
            ],
            [
                'name' => 'Politics',
                'slug' => 'politics',
                'description' => 'Political news, analysis, policy discussions, and current events',
                'icon' => '🏛️',
                'color' => '#EF4444'
            ],
            [
                'name' => 'Health',
                'slug' => 'health',
                'description' => 'Health and wellness, medical research, fitness, and nutrition',
                'icon' => '🏥',
                'color' => '#14B8A6'
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Personal finance, investing, market analysis, and financial advice',
                'icon' => '💰',
                'color' => '#F59E0B'
            ],
            [
                'name' => 'Sports',
                'slug' => 'sports',
                'description' => 'Sports news, analysis, commentary, and athlete profiles',
                'icon' => '⚽',
                'color' => '#06B6D4'
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational content, learning resources, and academic insights',
                'icon' => '📚',
                'color' => '#6366F1'
            ],
            [
                'name' => 'Entertainment',
                'slug' => 'entertainment',
                'description' => 'Movies, TV shows, celebrity news, and pop culture',
                'icon' => '🎬',
                'color' => '#F97316'
            ],
            [
                'name' => 'Travel',
                'slug' => 'travel',
                'description' => 'Travel guides, destination reviews, and adventure stories',
                'icon' => '✈️',
                'color' => '#0891B2'
            ],
            [
                'name' => 'Food & Cooking',
                'slug' => 'food-cooking',
                'description' => 'Recipes, restaurant reviews, culinary tips, and food culture',
                'icon' => '🍳',
                'color' => '#DC2626'
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'Graphic design, UI/UX, architecture, and creative design work',
                'icon' => '🎨',
                'color' => '#7C3AED'
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Digital marketing, advertising, branding, and growth strategies',
                'icon' => '📢',
                'color' => '#059669'
            ],
            [
                'name' => 'Productivity',
                'slug' => 'productivity',
                'description' => 'Productivity tips, time management, and workflow optimization',
                'icon' => '⚡',
                'color' => '#EA580C'
            ],
            [
                'name' => 'Philosophy',
                'slug' => 'philosophy',
                'description' => 'Philosophical discussions, ethics, and deep thinking',
                'icon' => '🤔',
                'color' => '#4338CA'
            ],
            [
                'name' => 'History',
                'slug' => 'history',
                'description' => 'Historical analysis, stories, and lessons from the past',
                'icon' => '📜',
                'color' => '#92400E'
            ],
            [
                'name' => 'Climate & Environment',
                'slug' => 'climate-environment',
                'description' => 'Climate change, environmental news, and sustainability',
                'icon' => '🌱',
                'color' => '#16A34A'
            ],
            [
                'name' => 'Psychology',
                'slug' => 'psychology',
                'description' => 'Psychology insights, mental health, and human behavior',
                'icon' => '🧠',
                'color' => '#BE185D'
            ],
            [
                'name' => 'Writing',
                'slug' => 'writing',
                'description' => 'Writing tips, author interviews, and literary discussions',
                'icon' => '✍️',
                'color' => '#0369A1'
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
    }
}





