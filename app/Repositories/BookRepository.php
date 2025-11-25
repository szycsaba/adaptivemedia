<?php

namespace App\Repositories;

use App\Models\Book;

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
}