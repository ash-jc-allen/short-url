<?php

namespace AshAllenDesign\ShortURL\Controllers;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Http\RedirectResponse;

class ShortURLController
{
    /**
     * Redirect the user to the intended destination
     * URL.
     *
     * @param  Resolver  $resolver
     * @param  string  $shortURLKey
     * @return RedirectResponse
     */
    public function __invoke(Resolver $resolver, string $shortURLKey): RedirectResponse
    {
        $shortURL = ShortURL::where('url_key', $shortURLKey)->firstOrFail();

        $resolver->handleVisit(request(), $shortURL);

        return redirect($shortURL->destination_url, 301);
    }
}