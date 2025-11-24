<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3, true),
            'author_id' => Author::inRandomOrder()->first()?->id ?? Author::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'release_date' => fake()->date('Y-m-d', 'now'),
            'price_huf' => fake()->numberBetween(1000, 10000),
        ];
    }
}

