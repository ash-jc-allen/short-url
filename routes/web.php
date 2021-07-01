<?php

app('router')->get(
    '/short/{shortURLKey}',
    [
        'as' => 'short-url.invoke',
        'uses' => 'AshAllenDesign\ShortURL\Controllers\ShortURLController'
    ]
);
