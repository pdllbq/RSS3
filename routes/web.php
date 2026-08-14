<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\SystemInfoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ru'); // In future, middleware will be used.

// Language group
Route::group(['prefix' => '{lang}', 'where' => ['lang' => '[a-zA-Z]{2}']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/rss', [RssController::class, 'index'])->name('rss.index');
    Route::get('/rss/{categorySlug}', [RssController::class, 'category'])
        ->where('categorySlug', '.*')
        ->name('rss.category');
});

// System info
Route::get('/s/{itemId}', [SystemInfoController::class, 'index'])->name('system.info');
