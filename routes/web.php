<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Substack Content Aggregation Hub API',
        'version' => '1.0.0',
        'message' => 'Welcome to the API',
    ]);
});





