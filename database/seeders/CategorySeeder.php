<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fantasy',
            'Science Fiction',
            'Mystery',
            'Romance',
            'Thriller',
            'Horror',
            'Adventure',
            'Historical Fiction',
            'Biography',
            'Non-Fiction',
            'Young Adult',
            'Children\'s',
            'Poetry',
            'Drama',
            'Comedy',
            'Crime',
            'Western',
            'Philosophy',
            'Self-Help',
            'Cookbook',
        ];

        foreach ($categories as $categoryName) {
            Category::create(['name' => $categoryName]);
        }
    }
}

