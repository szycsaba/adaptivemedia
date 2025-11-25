<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddBookRequest;
use App\Http\Requests\GetBookRequest;
use App\Http\Requests\SearchBooksRequest;
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

    public function getBookById(GetBookRequest $request, BookService $bookService): JsonResponse
    {
        $id = (int) $request->validated()['id'];
        $response = $bookService->getBookById($id);

        return response()->json(
            $response->toArray(),
            $response->status
        );
    }

    public function searchBooks(SearchBooksRequest $request, BookService $bookService): JsonResponse
    {
        $query = $request->validated()['query'];
        $response = $bookService->searchBooks($query);

        return response()->json(
            $response->toArray(),
            $response->status
        );
    }

}
