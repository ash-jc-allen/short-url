<?php

use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Route;

Route::domain(ShortURL::domain())->middleware(ShortURL::middleware())->group(function () {
    Route::get(ShortURL::routeUrl(), 'AshAllenDesign\ShortURL\Controllers\ShortURLController')
        ->name('short-url.invoke');
});
