<?php

use Illuminate\Support\Facades\Route;

Route::get(config('short-url.prefix', '/short').'/{shortURLKey}', 'AshAllenDesign\ShortURL\Controllers\ShortURLController')->name('short-url.invoke');
