<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'getBooks']);
Route::post('/books', [BookController::class, 'addBook']);
Route::get('/books/{id}', [BookController::class, 'getBookById']);