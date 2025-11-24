<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{

    public function getBooks(BookService $bookService): JsonResponse
    {
        $response = $bookService->getBooks();

        return response()->json(
            $response->toArray(),
            $response->status
        );
    }
}
