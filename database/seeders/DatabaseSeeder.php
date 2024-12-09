<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Posts;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(6)->create();

        Posts::factory(24)->create();

        Category::create([
            'name' => 'Web Programming',
            'slug' => 'web-programming',
        ]);

        Category::create([
            'name' => 'Web Design',
            'slug' => 'web-design',
        ]);

        Category::create([
            'name' => 'Personal',
            'slug' => 'personal',
        ]);

        Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
        ]);

        Category::create([
            'name' => 'Travel',
            'slug' => 'travel',
        ]);

        Category::create([
            'name' => 'Food & Cooking',
            'slug' => 'food-cooking',
        ]);

        Category::create([
            'name' => 'Fitness',
            'slug' => 'fitness',
        ]);

        Category::create([
            'name' => 'Education',
            'slug' => 'education',
        ]);

        Category::create([
            'name' => 'Business',
            'slug' => 'business',
        ]);

        Category::create([
            'name' => 'Entertainment',
            'slug' => 'entertainment',
        ]);

        Category::create([
            'name' => 'Sports',
            'slug' => 'sports',
        ]);

        Category::create([
            'name' => 'Art & Design',
            'slug' => 'art-design',
        ]);

        Category::create([
            'name' => 'Health & Wellness',
            'slug' => 'health-wellness',
        ]);

        Category::create([
            'name' => 'Science',
            'slug' => 'science',
        ]);

        Category::create([
            'name' => 'Photography',
            'slug' => 'photography',
        ]);

        Category::create([
            'name' => 'Music',
            'slug' => 'music',
        ]);

        Category::create([
            'name' => 'Movies',
            'slug' => 'movies',
        ]);
    }
}
