<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/books', [BookController::class, 'getBooks']);
Route::post('/books', [BookController::class, 'addBook']);
Route::get('/books/search', [BookController::class, 'searchBooks']);
Route::get('/books/{id}', [BookController::class, 'getBookById']);
Route::get('/statistics/expensive-books', [BookController::class, 'getExpensiveBooks']);
Route::get('/statistics/popular-categories', [BookController::class, 'getPopularCategories']);
Route::get('/statistics/top-fantasy-and-sci-fi', [BookController::class, 'getTopFantasyAndSciFiBooks']);
