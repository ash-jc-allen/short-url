<?php

use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Route;

Route::get('/'.ShortURL::prefix().'/{shortURLKey}', 'AshAllenDesign\ShortURL\Controllers\ShortURLController')->name('short-url.invoke');
