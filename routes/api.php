<?php

use Illuminate\Support\Facades\Route;

Route::get('/books', function () {
    return response()->json([
        'success' => true,
        'message' => 'Ok'
    ], 200);
});