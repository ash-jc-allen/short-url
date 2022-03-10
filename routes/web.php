<?php

use AshAllenDesign\ShortURL\Facades\ShortURL;
use Illuminate\Support\Facades\Route;

$prefix = ShortURL::prefix();
$prefix = $prefix ? "/$prefix/" : '';

Route::get("$prefix{shortURLKey}", 'AshAllenDesign\ShortURL\Controllers\ShortURLController')->name('short-url.invoke');
