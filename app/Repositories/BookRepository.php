<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository implements BookRepositoryInterface
{
    public function getBooks(): array
    {
        return Book::with(['author', 'category'])->get()->toArray();
    }
}