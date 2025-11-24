<?php

namespace App\Services;

use App\DTO\ServiceResponse;
use App\Http\Resources\BookResource;
use App\Repositories\BookRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

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
        } catch (Throwable $e) {
            return new ServiceResponse(
                success: false,
                message: 'An error occurred while fetching books',
                error_message: $e->getMessage(),
                status: 500
            );
        }
    }
}