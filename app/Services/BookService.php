<?php

namespace App\Services;

use App\DTO\ServiceResponse;
use App\Http\Resources\BookResource;
use App\Repositories\BookRepositoryInterface;
use App\Services\Exchange\ExchangeRateClientInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class BookService
{
    public function __construct(
        private BookRepositoryInterface $repo,
        private ExchangeRateClientInterface $exchange
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

    public function getBookById(int $id): ServiceResponse
    {
        try {
            $book = $this->repo->getBookById($id);

            if ($book === null) {
                return new ServiceResponse(false, 'Book not found', null, 404);
            }

            $priceHuf = (int) $book['price_huf'];

            try {
                $priceEur = $this->exchange->convert('HUF', 'EUR', $priceHuf);
            } catch (Throwable $e) {
                Log::warning('EUR conversion failed: '.$e->getMessage());
            }

            if ($priceEur !== null) {
                $book['price_eur'] = $priceEur;
            }

            $resource = (new BookResource($book))->toArray(request());

            return new ServiceResponse(true, 'Book retrieved successfully', $resource, 200);
        } catch (QueryException $e) {
            Log::error('An error occurred while fetching book by id: ' . $e->getMessage());
            return new ServiceResponse(false, 'An error occurred while fetching book by id', null, 500);
        }
    }

    public function searchBooks(string $query): ServiceResponse
    {
        try {
            $books = $this->repo->searchBooks($query);

            $resource = BookResource::collection($books)->toArray(request());

            return new ServiceResponse(true, 'Books search completed successfully', $resource, 200);
        } catch (QueryException $e) {
            Log::error('An error occurred while searching books: ' . $e->getMessage());
            return new ServiceResponse(false, 'An error occurred while searching books', null, 500);
        }
    }
}