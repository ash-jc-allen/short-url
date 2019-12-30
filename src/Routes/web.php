<?php

Route::get('/short/{shortURLKey}', 'AshAllenDesign\ShortURL\Controllers\ShortURLController')->name('short-url.invoke');
