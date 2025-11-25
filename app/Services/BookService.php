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
                return new ServiceResponse(
                    success: false, 
                    message: 'Book not found', 
                    data: null, 
                    status: 404
                );
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

            return new ServiceResponse(
                success: true, 
                message: 'Book retrieved successfully', 
                data: $resource, 
                status: 200
            );
        } catch (QueryException $e) {
            Log::error('An error occurred while fetching book by id: ' . $e->getMessage());
            return new ServiceResponse(
                success: false, 
                message: 'An error occurred while fetching book by id', 
                data: null, 
                status: 500
            );
        }
    }

    public function searchBooks(string $query): ServiceResponse
    {
        try {
            $books = $this->repo->searchBooks($query);

            $resource = BookResource::collection($books)->toArray(request());

            return new ServiceResponse(
                success: true,
                message: 'Books search completed successfully',
                data: $resource,
                status: 200
            );
        } catch (QueryException $e) {
            Log::error('An error occurred while searching books: ' . $e->getMessage());
            return new ServiceResponse(
                success: false, 
                message: 'An error occurred while searching books', 
                data: null, 
                status: 500
            );
        }
    }

    public function getExpensiveBooks(): ServiceResponse
    {
        try {
            $books = $this->repo->getExpensiveBooks();
            $resource = BookResource::collection($books)->toArray(request());
    
            return new ServiceResponse(
                success: true, 
                message: 'Books above average price retrieved successfully', 
                data: $resource, 
                status: 200
            );
        } catch (QueryException $e) {
            Log::error('Error while fetching expensive books: ' . $e->getMessage());
    
            return new ServiceResponse(
                success: false,
                message: 'Error while fetching expensive books',
                data: null,
                status: 500
            );
        }
    }

    public function getPopularCategories(): ServiceResponse
    {
        try {
            $data = $this->repo->getPopularCategories();

            return new ServiceResponse(
                success: true, 
                message: 'Popular categories retrieved successfully', 
                data: $data, 
                status: 200
            );
        } catch (QueryException $e) {
            Log::error('Error fetching popular categories: '.$e->getMessage());
            return new ServiceResponse(
                success: false, 
                message: 'Unable to fetch popular categories', 
                data: null, 
                status: 500
            );
        }
    }

    public function getTopFantasyAndSciFiBooks(): ServiceResponse
    {
        try {
            $data = $this->repo->getTopFantasyAndSciFiBooks();
            $resource = BookResource::collection($data)->toArray(request());
    
            return new ServiceResponse(
                success: true, 
                message: 'Top Fantasy & Sci-Fi books retrieved successfully', 
                data: $resource, 
                status: 200
            );
        } catch (QueryException $e) {
            Log::error('Error fetching top fantasy and sci-fi: '.$e->getMessage());
            return new ServiceResponse(
                success: false, 
                message: 'Unable to fetch data', 
                data: null, 
                status: 500
            );
        }
    }
}