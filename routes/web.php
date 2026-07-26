<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ru'); //In future, middleware will be used.

//Language group
Route::group(['prefix' => '{lang}', 'where' => ['lang' => '[a-zA-Z]{2}']], function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});