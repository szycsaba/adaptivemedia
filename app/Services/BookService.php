<?php

namespace App\Services;

use App\DTO\ServiceResponse;
use App\Http\Resources\BookResource;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $repo
    ) {}

    public function getBooks(): ServiceResponse
    {
        try {
            return DB::transaction(function () {
                $books = $this->repo->getBooks();

                $resource = BookResource::collection($books)->toArray(request());

                return new ServiceResponse(
                    success: true,
                    message: 'Books listed successfully',
                    data: $resource,
                    status: 200
                );
            });
        } catch (QueryException $e) {
            Log::error('An error occurred while fetching books: ' . $e->getMessage());
            return new ServiceResponse(
                success: false,
                message: 'An error occurred while fetching books',
                status: 500
            );
        }
    }

    public function addBook(array $params): ServiceResponse
    {
        try {
            return DB::transaction(function () use ($params) {
                $book = $this->repo->addBook($params);

                $resource = (new BookResource($book))->toArray(request());

                return new ServiceResponse(
                    success: true,
                    message: 'Book created successfully',
                    data: $resource,
                    status: 201
                );
            });
        } catch (QueryException $e) {
            Log::error('An error occurred while creating book: ' . $e->getMessage());
            return new ServiceResponse(
                success: false,
                message: 'An error occurred while creating book',
                status: 500
            );
        }
    }
}