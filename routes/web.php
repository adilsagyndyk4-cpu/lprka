<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('catalog.index'));

Route::prefix('catalog')->name('catalog.')->controller(CatalogController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/stats', 'stats')->name('stats');
    Route::get('/create', 'create')->name('create');
    Route::post('/', 'store')->name('store');
    Route::get('/zone/{zone?}', 'byZone')->name('zone');
    Route::get('/code/{code}', 'byCode')
        ->where('code', '[A-Z]{3}-[0-9]{3}')->name('code');
    Route::get('/{computer}', 'show')->whereNumber('computer')->name('show');
    Route::get('/{computer}/edit', 'edit')->whereNumber('computer')->name('edit');
    Route::put('/{computer}', 'update')->whereNumber('computer')->name('update');
    Route::delete('/{computer}', 'destroy')->whereNumber('computer')->name('destroy');
});

Route::get('/feedback', [PageController::class, 'feedbackForm'])->name('feedback.form');
Route::post('/feedback', [PageController::class, 'feedbackSend'])->name('feedback.send');

Route::get('/hello/{name?}', function (string $name = 'гость') {
    return "Привет, {$name}!";
})->where('name', '[\p{L}]+');
