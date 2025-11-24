<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            'J.K. Rowling' => Author::where('name', 'J.K. Rowling')->first()->id,
            'George R.R. Martin' => Author::where('name', 'George R.R. Martin')->first()->id,
            'Isaac Asimov' => Author::where('name', 'Isaac Asimov')->first()->id,
            'J.R.R. Tolkien' => Author::where('name', 'J.R.R. Tolkien')->first()->id,
            'Frank Herbert' => Author::where('name', 'Frank Herbert')->first()->id,
        ];

        $categories = [
            'Fantasy' => Category::where('name', 'Fantasy')->first()->id,
            'Science Fiction' => Category::where('name', 'Science Fiction')->first()->id,
            'Mystery' => Category::where('name', 'Mystery')->first()->id,
            'Romance' => Category::where('name', 'Romance')->first()->id,
            'Thriller' => Category::where('name', 'Thriller')->first()->id,
        ];

        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'author_id' => $authors['J.K. Rowling'],
                'category_id' => $categories['Fantasy'],
                'release_date' => '1997-06-26',
                'price_huf' => 3500,
            ],
            [
                'title' => 'A Game of Thrones',
                'author_id' => $authors['George R.R. Martin'],
                'category_id' => $categories['Fantasy'],
                'release_date' => '1996-08-01',
                'price_huf' => 4200,
            ],
            [
                'title' => 'Foundation',
                'author_id' => $authors['Isaac Asimov'],
                'category_id' => $categories['Science Fiction'],
                'release_date' => '1951-05-01',
                'price_huf' => 3800,
            ],
            [
                'title' => 'The Lord of the Rings',
                'author_id' => $authors['J.R.R. Tolkien'],
                'category_id' => $categories['Fantasy'],
                'release_date' => '1954-07-29',
                'price_huf' => 5500,
            ],
            [
                'title' => 'Dune',
                'author_id' => $authors['Frank Herbert'],
                'category_id' => $categories['Science Fiction'],
                'release_date' => '1965-08-01',
                'price_huf' => 4500,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // Generate books with Faker
        $allAuthors = Author::pluck('id')->toArray();
        $allCategories = Category::pluck('id')->toArray();

        Book::factory()->count(200)->create([
            'author_id' => fn() => fake()->randomElement($allAuthors),
            'category_id' => fn() => fake()->randomElement($allCategories),
        ]);
    }
}

