<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'J.K. Rowling'],
            ['name' => 'George R.R. Martin'],
            ['name' => 'Isaac Asimov'],
            ['name' => 'J.R.R. Tolkien'],
            ['name' => 'Frank Herbert'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }

        // Generate authors with Faker
        Author::factory()->count(50)->create();
    }
}

