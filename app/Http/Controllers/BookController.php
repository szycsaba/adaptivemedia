<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
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

    public function addBook(AddBookRequest $request, BookService $bookService): JsonResponse
    {
        $response = $bookService->addBook($request->validated());

        return response()->json(
            $response->toArray(),
            $response->status
        );
    }
}
