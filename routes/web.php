<?php

use AshAllenDesign\ShortURL\Facades\ShortURL;

if (! config('short-url.disable_default_route')) {
    ShortURL::routes();
}
