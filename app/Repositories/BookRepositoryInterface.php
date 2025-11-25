<?php

namespace App\Repositories;

interface BookRepositoryInterface
{
    public function getBooks(): array;
    public function addBook(array $params): array;
    public function getBookById(int $id): ?array;
    public function searchBooks(string $query): array;
}