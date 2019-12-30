<?php

namespace AshAllenDesign\ShortURL\Controllers;

use AshAllenDesign\ShortURL\Classes\Resolver;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShortURLController
{
    /**
     * Redirect the user to the intended destination
     * URL.
     *
     * @param  Request  $request
     * @param  Resolver  $resolver
     * @param  string  $shortURLKey
     * @return RedirectResponse
     */
    public function __invoke(Request $request, Resolver $resolver, string $shortURLKey): RedirectResponse
    {
        if ($request->route()->getName() === 'short-url.invoke'
            && config('short-url.custom_routing_enabled')) {
            abort(404);
        }

        $shortURL = ShortURL::where('url_key', $shortURLKey)->firstOrFail();

        $resolver->handleVisit(request(), $shortURL);

        return redirect($shortURL->destination_url, 301);
    }
}
