<?php

use AshAllenDesign\ShortURL\Controllers\ShortURLController;
use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Route;

Route::get('/'.ShortURL::prefix().'/{shortURLKey}', ShortURLController::class)->name('short-url.invoke');
