<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Facades\DB;

class BookRepository implements BookRepositoryInterface
{
    public function getBooks(): array
    {
        return Book::with(['author', 'category'])->get()->toArray();
    }

    public function addBook(array $params): array
    {
        $book = new Book();
        $book->title = $params['title'];
        $book->author_id = $params['author_id'];
        $book->category_id = $params['category_id'];
        $book->release_date = $params['release_date'];
        $book->price_huf = $params['price_huf'];
        $book->save();

        return Book::with(['author', 'category'])
            ->findOrFail($book->id)
            ->toArray();
    }

    public function getBookById(int $id): ?array
    {
        $book = Book::with(['author', 'category'])->find($id);

        if ($book === null) {
            return null;
        }

        return $book->toArray();
    }

    public function searchBooks(string $query): array
    {
        return Book::with(['author', 'category'])
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhereHas('author', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('category', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->get()
            ->toArray();
    }

    public function getExpensiveBooks(): array
    {
        return Book::with(['author', 'category'])
            ->where('price_huf', '>', function($query) {
                $query->selectRaw('AVG(price_huf)')->from('books');
            })
            ->get()
            ->toArray();
    }

    public function getPopularCategories(): array
    {
        return DB::table('categories')
            ->join('books', 'books.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('COUNT(books.id) as book_count'),
                DB::raw('ROUND(AVG(books.price_huf), 2) as avg_price_huf')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('book_count')
            ->limit(3)
            ->get()
            ->toArray();
    }

    public function getTopFantasyAndSciFiBooks(): array
    {
        return Book::with(['author', 'category'])
            ->whereHas('category', function($q) {
                $q->whereIn('name', ['Fantasy', 'Science Fiction']);
            })
            ->orderByDesc('price_huf')
            ->limit(3)
            ->get()
            ->toArray();
    }
}