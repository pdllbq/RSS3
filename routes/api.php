<?php

use App\Http\Controllers\Api\ItemController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.allow.ip')->group(function () {
    Route::get('/items/category/{category}', [ItemController::class, 'byCategory'])
        ->whereNumber('category');
});
