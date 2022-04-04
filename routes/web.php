<?php

use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Route;

Route::middleware(ShortURL::middleware())->group(function () {
    Route::get('/'.ShortURL::prefix().'/{shortURLKey}', 'AshAllenDesign\ShortURL\Controllers\ShortURLController')->name('short-url.invoke');
});
