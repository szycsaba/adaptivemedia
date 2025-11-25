<?php

namespace App\Repositories;

interface BookRepositoryInterface
{
    public function getBooks(): array;
    public function addBook(array $params): array;
}