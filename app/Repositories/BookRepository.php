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
}